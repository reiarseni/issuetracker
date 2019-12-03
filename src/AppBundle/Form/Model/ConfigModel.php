<?php

declare(strict_types=1);

namespace AppBundle\Form\Model;

class ConfigModel
{
    /**
     * @var string
     */
    private $nombre;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $facebookUrl;

    /**
     * @var bool
     */
    private $mostrarContacto;

    /**
     * @var bool
     */
    private $enMantenimiento;

    /**
     * @var string
     */
    private $version;

    /**
     * AsignacionModel constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getFacebookUrl()
    {
        return $this->facebookUrl;
    }

    /**
     * @param string $facebookUrl
     */
    public function setFacebookUrl($facebookUrl)
    {
        $this->facebookUrl = $facebookUrl;
    }

    /**
     * @return string
     */
    public function isMostrarContacto()
    {
        return (bool) $this->mostrarContacto;
    }

    /**
     * @param string $mostrarContacto
     */
    public function setMostrarContacto($mostrarContacto)
    {
        $this->mostrarContacto = $mostrarContacto;
    }

    /**
     * @return string
     */
    public function isEnMantenimiento()
    {
        return (bool) $this->enMantenimiento;
    }

    /**
     * @param string $enMantenimiento
     */
    public function setEnMantenimiento($enMantenimiento)
    {
        $this->enMantenimiento = $enMantenimiento;
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion($version)
    {
        $this->version = $version;
    }
}
