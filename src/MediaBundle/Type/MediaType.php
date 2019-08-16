<?php

namespace MediaBundle\Type;


use MediaBundle\Entity\Media;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\JsonArrayType;


class MediaType extends JsonArrayType
{
    const TYPE = 'media';

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return json_encode($value);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if ($value === null || $value === '') {
            return new Media();
        }

        $value = (is_resource($value)) ? stream_get_contents($value) : $value;


        $json = json_decode($value, true);
        $res = new Media();
        if (isset($json['alt'])) {
            $res->setAlt($json['alt']);
        }
        if (isset($json['path'])) {
            $res->setPath($json['path']);
        }
        if (isset($json['conf'])) {
            $res->setConf($json['conf']);
        }
        if (isset($json['name'])) {
            $res->setName($json['name']);
        }
        return $res;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::TYPE;
    }


}