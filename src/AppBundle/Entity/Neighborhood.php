<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Neighborhood.
 *
 * @ORM\Table(name="neighborhood", indexes={@ORM\Index(name="city_id", columns={"city_id"})})
 * @ORM\Entity
 */
class Neighborhood
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="bigint")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var \AppBundle\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    private $city;
}
