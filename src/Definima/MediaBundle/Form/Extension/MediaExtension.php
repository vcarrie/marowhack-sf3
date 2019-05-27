<?php

namespace Definima\MediaBundle\Form;

use Symfony\Component\Form\AbstractTypeExtension;
use Definima\MediaBundle\Form\Type\MediaType;


class MediaExtension extends AbstractTypeExtension
{

    /**
     * Returns the name of the type being extended.
     *
     * @return string The name of the type being extended
     */
    public function getExtendedType()
    {
        return MediaType::class;
    }
}