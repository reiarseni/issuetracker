<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Person.
 *
 * @ORM\Table(name="person", indexes={@ORM\Index(name="city_id", columns={"city_id"}), @ORM\Index(name="neighborhood_id", columns={"neighborhood_id"})})
 * @ORM\Entity
 */
class Person
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
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255, nullable=false)
     */
    private $lastName;

    /**
     * @var \AppBundle\Entity\City
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\City")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="city_id", referencedColumnName="id")
     * })
     */
    private $city;

    /**
     * @var \AppBundle\Entity\Neighborhood
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Neighborhood")
     * @ORM\JoinColumns({
     *     @ORM\JoinColumn(name="neighborhood_id", referencedColumnName="id")
     * })
     */
    private $neighborhood;
}
