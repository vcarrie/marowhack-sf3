<?php
namespace AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class MenuExtension extends AbstractExtension
{
    public function getFunctions()
    {
         return array(
            new TwigFunction('displayMenu', array($this, 'menu')),
        );
    }

    public function menu()
    {
       return \AdminBundle\Controller\DashboardController::get_menus();
    }

}