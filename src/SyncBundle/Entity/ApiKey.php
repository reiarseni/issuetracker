<?php

namespace SyncBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity()
 * @ORM\Table(name="apikey")
 */
class ApiKey
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
     * Url del sitio centralizado
     *
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $masterUrl;

    /**
     * Apikey del usuario
     *
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $apiKey;

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
     * Set masterUrl
     *
     * @param string $masterUrl
     *
     * @return ApiKey
     */
    public function setMasterUrl($masterUrl)
    {
        $this->masterWeb = $masterUrl;

        return $this;
    }

    /**
     * Get masterUrl
     *
     * @return string
     */
    public function getMasterUrl()
    {
        return $this->masterUrl;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return ApiKey
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set apiKey
     *
     * @param string $apiKey
     *
     * @return ApiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
