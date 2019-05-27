<?php
namespace AdminBundle\Twig;

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
       return \AdminBundle\Controller\DashboardController::get_menus();
    }

}