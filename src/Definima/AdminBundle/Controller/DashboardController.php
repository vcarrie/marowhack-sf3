<?php

namespace Definima\AdminBundle\Controller;

use Definima\AdminBundle\Entity\Admin;
use Definima\RucherBundle\Entity\Apiculteur;
use Definima\RucherBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;

class DashboardController extends Controller
{

    public function indexAction()
    {
        /** @var Admin $cr */
        $cr = $this->getUser();

//        $user = new Client();
//
//        $emailCli = 'client@definima.com';
//        $password = "definima233";
//
//        $discriminator = $this->container->get('pugx_user.manager.user_discriminator');
//        $discriminator->setClass('Definima\RucherBundle\Entity\Client');
//        $userManager = $this->container->get('pugx_user_manager');
//
//        $user->setEmail($emailCli);
//        $user->setNom('Client');
//        $user->setUsername($emailCli);
//        $user->setPlainPassword($password);
//        $user->setRoles(array('ROLE_CLIENT'));
//
//        $userManager->updateUser($user, true);
//
//        $em = $this->getDoctrine()->getManager();
//        $em->persist($user);
//        $em->flush();

        $bundles = DashboardController::get_menus();
        return $this->render('DefinimaAdminBundle:Dashboard:index.html.twig', array(
            'cr' => $cr,
            'menus' => $bundles,
            'active' => array('Tableau de bord'),
        ));
    }



    public static function get_menus()
    {
        $bundles = array();
        $finder = new Finder();

        foreach ($finder->in(__DIR__ . '/..\/..\/[a-zA-Z]*Bundle\/Controller/\Admin/\/') as $file) {

            $filePath = str_replace("\\", "/", $file->getRealpath());

            $bundle = array();
            preg_match('/.*\/([a-zA-Z]*Bundle)\/Controller\/Admin\/([a-zA-Z]*)Controller.php/', $filePath, $bundle);

            if (isset($bundle[1])) {
                $bundles[] = "Definima" . $bundle[1] . ":Admin:_menu.html.twig";
                $bundles[] = "Definima" . $bundle[1] . ":Admin\\" . $bundle[2] . ":_menu.html.twig";
            }
        }
        $bundles = array_unique($bundles);
//
//        $bundles = array(
//            "DefinimaOffreBundle:Admin\Offre:_menu.html.twig",
//            "DefinimaCandidatBundle:Admin\Candidat:_menu.html.twig",
//            "DefinimaOffreBundle:Admin\Client:_menu.html.twig",
//            "DefinimaOffreBundle:Admin\Groupe:_menu.html.twig",
//            "DefinimaOffreBundle:Admin\Fonction:_menu.html.twig",
//            "DefinimaMailBundle:Admin:_menu.html.twig",
//        );

        return $bundles;
    }

    function glob_recursive($pattern, $flags = 0)
    {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, glob_recursive($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }

}
