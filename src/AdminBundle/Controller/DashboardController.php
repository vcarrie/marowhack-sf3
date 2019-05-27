<?php

namespace AdminBundle\Controller;

use AdminBundle\Entity\Admin;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;

class DashboardController extends Controller {

    public function indexAction() {
        
        $cr = $this->getUser();

        $bundles = DashboardController::get_menus();
        return $this->render('AdminBundle:Dashboard:index.html.twig', array(
                    'cr' => $cr,
                    'menus' => $bundles,
                    'active' => array('Tableau de bord'),
        ));
    }

    public static function get_menus() {
        $bundles = array();
        $finder = new Finder();

        if(glob(__DIR__ . '/..\/..\/[a-zA-Z]*Bundle\/Controller/\Admin/\/')){

            foreach ($finder->in(__DIR__ . '/..\/..\/[a-zA-Z]*Bundle\/Controller/\Admin/\/') as $file) {

                $filePath = str_replace("\\", "/", $file->getRealpath());

                $bundle = array();
                preg_match('/.*\/([a-zA-Z]*Bundle)\/Controller\/Admin\/([a-zA-Z]*)Controller.php/', $filePath, $bundle);

                if (isset($bundle[1])) {
                    $bundles[] = $bundle[1] . ":Admin:_menu.html.twig";
                    $bundles[] = $bundle[1] . ":Admin\\" . $bundle[2] . ":_menu.html.twig";
                }
            }
        }
        
        return array_unique($bundles);
    }

}
