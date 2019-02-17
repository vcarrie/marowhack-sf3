<?php

namespace Definima\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('DefinimaAdminBundle:Default:index.html.twig');
    }
}
