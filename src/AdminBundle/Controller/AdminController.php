<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use SensioLabs\AnsiConverter\AnsiToHtmlConverter;
use AdminBundle\Entity\Admin;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller {

    /**
     * Lists all admin entities.
     *
     */
    public function indexAction() {
        $em = $this->getDoctrine()->getManager();

        $admins = $em->getRepository('AdminBundle:Admin')->findBy(array('isDeleted' => 0));
        $deleteForm = $this->createDeleteForm(0);

        $bundles = DashboardController::get_menus();
        return $this->render('AdminBundle:Admin:index.html.twig', array(
                    'menus' => $bundles,
                    'active' => array('Administration', 'Liste des administrateurs'),
                    'admins' => $admins,
                    'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Creates a new admin entity.
     *
     */
    public function newAction(Request $request) {
        $admin = new Admin();
        $form = $this->createForm('AdminBundle\Form\AdminType', $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $email = $admin->getEmail();
            $password = $form->get("newpass")->getData();
            $roles = array('ROLE_ADMIN');

            $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
            $discriminator->setClass('AdminBundle\Entity\Admin');
            $userManager = $this->container->get('pugx_user_manager');

            $admin->setUsername($email);
            $admin->setPlainPassword($password);
            $admin->setRoles($roles);

            $userManager->updateUser($admin, true);
            $this->addFlash(
                    'success', 'Admin enregistré !'
            );
            return $this->redirectToRoute('admin_gestion_admin_index', array('id' => $admin->getId()));
        }
        $bundles = DashboardController::get_menus();

        return $this->render('AdminBundle:Admin:new.html.twig', array(
                    'menus' => $bundles,
                    'active' => array('Administration', 'Liste des administrateurs'),
                    'admin' => $admin,
                    'form' => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing admin entity.
     *
     */
    public function editAction(Request $request, Admin $admin) {
        $editForm = $this->createForm('AdminBundle\Form\AdminType', $admin);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {

            $email = $admin->getEmail();
            $password = $editForm->get("newpass")->getData();
            $super_admin = $request->request->get('super_admin', 0);
            $roles = array('ROLE_ADMIN');
            $admin->setRoles($roles);

            if ((int) $super_admin) {
                $admin->setSuperAdmin(true);
                $admin->removeRole('ROLE_ADMIN');
            }

            $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
            $discriminator->setClass('AdminBundle\Entity\Admin');
            $userManager = $this->container->get('pugx_user_manager');

            $admin->setUsername($email);
            if (!empty($password)) {
                $admin->setPlainPassword($password);
            }

            $userManager->updateUser($admin, true);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('admin_gestion_admin_edit', array('id' => $admin->getId()));
        }

        $bundles = DashboardController::get_menus();
        return $this->render('AdminBundle:Admin:edit.html.twig', array(
                    'menus' => $bundles,
                    'active' => array('Administration', 'Liste des administrateurs'),
                    'admin' => $admin,
                    'form' => $editForm->createView(),
        ));
    }

    /**
     * Deletes a admin entity.
     *
     */
    public function deleteAction(Request $request, Admin $admin) {
        $form = $this->createDeleteForm($admin->getId());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($admin);
            $em->flush();
        }

        return $this->redirectToRoute('admin_gestion_admin_index');
    }

    /**
     * Creates a form to delete a admin entity.
     *
     * @param $id The admin id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id) {
        return $this->createFormBuilder()
                        ->setAction($this->generateUrl('admin_gestion_admin_delete', array('id' => $id)))
                        ->setMethod('DELETE')
                        ->getForm()
        ;
    }

    public function consoleAction() {

        $bundles = DashboardController::get_menus();
        $txt = '<span style="background-color: black; color: white"><h4>Bienvenue ' . $this->getUSer()->getPrenom() . ' ! </h4><br>Dossier de travail : "' . $this->get('kernel')->getRootDir() . '/.."</span>';

        return $this->render('AdminBundle:Admin:console.html.twig', array(
                    'menus' => $bundles,
                    'active' => array('Administration', 'Console'),
                    'welcome' => $txt
        ));
    }

    public function commandeAction($commande) {

        $cmd = 'php -d memory_limit=-1 bin/console ';

        switch ($commande) {
            case 'clearprod':
                $cmd .= 'cache:clear --env=prod';
                break;
            case 'cleardev':
                $cmd .= 'cache:clear';
                break;
            case 'schemaupdate':
                $cmd .= 'doctrine:schema:update --force';
                break;
            case 'assetsinstall':
                $cmd .= 'assets:install ';
                break;
            case 'asseticdump':
                $cmd .= 'assetic:dump';
                break;
            case 'fullassets':
                $cmd = $cmd . 'assets:install && ' . $cmd . 'assetic:dump ';
                break;
            default:
                $cmd = false;
                break;
        }

        if ($cmd) {
            chdir($this->get('kernel')->getRootDir() . '/..');
            $output = shell_exec($cmd);
            $converter = new AnsiToHtmlConverter();
            $html = $converter->convert($output);
            $datas = array('retour' => '<pre style="height: 100%;">' . $html . '</pre>');
        } else {
            $datas = array('retour' => 'Commande non trouvée !');
        }

        return new JsonResponse($datas);
    }

}
