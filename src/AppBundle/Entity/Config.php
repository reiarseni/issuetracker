<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config.
 *
 * @ORM\Table(name="config")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\ConfigRepository")
 */
class Config
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="AppBundle\Doctrine\UuidGenerator")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="the_key", type="string", length=255)
     */
    private $theKey;

    /**
     * @var string
     *
     * @ORM\Column(name="the_value", type="text")
     */
    private $theValue;

    public function __construct($theKey = '', $theValue = '')
    {
        $this->theKey = $theKey;
        $this->theValue = $theValue;
    }

    public function __toString()
    {
        return $this->getTheKey();
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
     * Set theKey.
     *
     * @param string $theKey
     *
     * @return Config
     */
    public function setTheKey($theKey)
    {
        $this->theKey = $theKey;

        return $this;
    }

    /**
     * Get theKey.
     *
     * @return string
     */
    public function getTheKey()
    {
        return $this->theKey;
    }

    /**
     * Set theValue.
     *
     * @param string $theValue
     *
     * @return Config
     */
    public function setTheValue($theValue)
    {
        $this->theValue = $theValue;

        return $this;
    }

    /**
     * Get theValue.
     *
     * @return string
     */
    public function getTheValue()
    {
        return $this->theValue;
    }
}
