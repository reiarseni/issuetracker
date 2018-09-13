<?php

namespace AppBundle\Model;

/**
 *
 */
class Bono
{

    /**
     * @var string
     */
    private $monto;

    /**
     * @var string
     */
    private $bono;

    /**
     * BonoModel constructor.
     */
    public function __construct($monto=0, $bono=0)
    {
        $this->monto = $monto;
        $this->bono = $bono;
    }

    /**
     * @return string
     */
    public function getMonto()
    {
        return $this->monto;
    }

    /**
     * @param string $monto
     */
    public function setMonto(string $monto)
    {
        $this->monto = $monto;
    }

    /**
     * @return string
     */
    public function getBono(): string
    {
        return $this->bono;
    }

    /**
     * @param string $bono
     */
    public function setBono(string $bono)
    {
        $this->bono = $bono;
    }

}
