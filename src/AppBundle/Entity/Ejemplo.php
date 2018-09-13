<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ejemplo")
 */
class Ejemplo
{

    /**
     * @ORM\Id
     * @ORM\Column(name="id", type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $colour;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Ejemplo2")
     * @ORM\JoinColumn(nullable=true)
     */
    private $ejemplo2;

    public function __construct()
    {
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return Ejemplo
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set colour.
     *
     * @param string $colour
     *
     * @return Ejemplo
     */
    public function setColour($colour)
    {
        $this->colour = $colour;

        return $this;
    }

    /**
     * Get colour.
     *
     * @return string
     */
    public function getColour()
    {
        return $this->colour;
    }

    public function __toString()
    {
        return $this->getName();
    }

    /**
     * Set ejemplo2
     *
     * @param \AppBundle\Entity\Ejemplo2 $ejemplo2
     *
     * @return Ejemplo
     */
    public function setEjemplo2(\AppBundle\Entity\Ejemplo2 $ejemplo2 = null)
    {
        $this->ejemplo2 = $ejemplo2;

        return $this;
    }

    /**
     * Get ejemplo2
     *
     * @return \AppBundle\Entity\Ejemplo2
     */
    public function getEjemplo2()
    {
        return $this->ejemplo2;
    }
}
