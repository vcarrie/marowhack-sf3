<?php

namespace Definima\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Finder\Finder;

class AdminController extends Controller
{
    public function indexAction()
    {
        return $this->render('DefinimaAdminBundle:Default:index.html.twig');
    }

}
