<?php

namespace MediaBundle\Entity;

use JsonSerializable;

class Media implements JsonSerializable
{


    /** @var string */
    private $path;

    /** @var string */
    private $alt;

    /** @var string */
    private $conf;

    /** @var string */
    private $name;

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param string $path
     */
    public function setPath($path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getAlt()
    {
        return $this->alt;
    }

    /**
     * @param string $alt
     */
    public function setAlt($alt)
    {
        $this->alt = $alt;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'path' => $this->path,
            'alt' => $this->alt,
            'conf' => $this->conf,
            'name' => $this->name
        ];
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        $path = urldecode($this->path);
        if (!empty($path) && $path[0] === '/') {
            return substr($path, 1);
        }
        return $path;
    }

    public function __toString()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getConf()
    {
        return $this->conf;
    }

    /**
     * @param string $conf
     */
    public function setConf($conf)
    {
        $this->conf = $conf;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


}
