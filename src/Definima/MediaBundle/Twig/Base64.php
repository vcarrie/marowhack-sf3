<?php

    namespace Definima\MediaBundle\Twig;

class Base64 extends \Twig_Extension {
    
    public function getFilters(){
        return array(
            new \Twig_SimpleFilter('base64_encode', 'base64_encode'),
            new \Twig_SimpleFilter('base64_decode', 'base64_decode')
        );
    }
    
    public function getName(){
        return "base64";
    }
}