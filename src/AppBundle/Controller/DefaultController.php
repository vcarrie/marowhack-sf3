<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    public function indexAction(Request $request)
    {
        $seoPage = $this->container->get('sonata.seo.page');
        $seoPage->setTitle('Accueil')
                ->addMeta('name', 'description', 'test description homepage')
                ->addMeta('property', 'og:description', 'test description homepage');
        
        
        // replace this example code with whatever you need
        return $this->render('@App/default/index.html.twig');
    }

    public function changeAction(Request $request)
    {
        return $this->render('@App/default/index.html.twig');
    }
}
