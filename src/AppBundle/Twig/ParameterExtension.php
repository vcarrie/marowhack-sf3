<?php
namespace AppBundle\Twig;

use Symfony\Component\DependencyInjection\Container;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ParameterExtension extends AbstractExtension
{
    /**
     * @var Container $container
     */
    protected $container;

    /**
     * Constructor
     *
     */
    public function __construct($container)
    {
        $this->container = $container;

    }

    public function getFunctions()
    {
         return array(
            new TwigFunction('parameter', array($this, 'getParameter')),
        );
    }

    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }

}