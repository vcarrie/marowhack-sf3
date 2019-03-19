<?php
namespace Definima\AdminBundle\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MenuExtension extends AbstractExtension
{
    public function getFilters()
    {
         return array(
            new TwigFilter('menu', array($this, 'menu')),
        );
    }

    public function menu()
    {
       return \Definima\AdminBundle\Controller\DashboardController::get_menus();
    }

}