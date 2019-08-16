<?php

namespace MediaBundle\Twig;

use MediaBundle\Entity\Media;
use MediaBundle\Helpers\FileManager;
use Symfony\Component\Routing\Router;
use Twig\TwigFunction;

class GenerateMediaLinkExtension extends \Twig_Extension
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var String
     */
    private $configuration;

    /**
     * OrderExtension constructor.
     *
     * @param Router $router
     */
    public function __construct(Router $router, $configuration)
    {
        $this->configuration = $configuration;
        $this->router = $router;
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('generateMediaLink', array($this, 'generateMediaLink')),
        );
    }

    public function generateMediaLink(Media $Media, $thumbnail=null)
    {
        $uri = $this->router->generate('media_download', ['path' => $Media->getConf().'__'.basename($Media->getPath()).'__'.strtolower(preg_replace("/[A-Z]+/", "_$0", $Media->getName())), 'version' => $thumbnail]);

        $domain = '';
        if ($this->configuration['use_external_domain']) {
            $domain = $this->configuration['domain'];
        }

        return $domain.$uri;
    }
}
