<?php

namespace NotifyBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserLoginLog
 *
 * @ORM\Table(name="user_login_log")
 * @ORM\Entity
 */
class UserLoginLog
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
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     */
    private $user;

    /**
     * @var string
     *
     * @ORM\Column(name="user_name", type="string", length=100, nullable=false)
     */
    private $userName;

    /**
     * @var string
     *
     * @ORM\Column(name="operation", type="string", length=100, nullable=true)
     */
    private $operation;

    /**
     * @var string
     *
     * @ORM\Column(name="show_text", type="string", length=255, nullable=false)
     */
    private $showText;

    /**
     * @var array
     *
     * @ORM\Column(name="url", type="array", nullable=true)
     */
    private $url;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_address", type="string", length=39, nullable=true)
     */
    private $ipAddress;

    /**
     * @var string
     *
     * @ORM\Column(name="user_agent", type="string", length=255, nullable=true)
     */
    private $userAgent;

    /**
     * Constructor
     */
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
     * Set ipAddress.
     *
     * @param string $ipAddress
     *
     * @return UserLoginLog
     */
    public function setIpAddress($ipAddress)
    {
        $this->ipAddress = $ipAddress;

        return $this;
    }

    /**
     * Get ipAddress.
     *
     * @return string
     */
    public function getIpAddress()
    {
        return $this->ipAddress;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return UserLoginLog
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set userName.
     *
     * @param string $userName
     *
     * @return UserLoginLog
     */
    public function setUserName($userName)
    {
        $this->userName = $userName;

        return $this;
    }

    /**
     * Get userName.
     *
     * @return string
     */
    public function getUserName()
    {
        return $this->userName;
    }

    /**
     * Set showText.
     *
     * @param string $showText
     *
     * @return UserLoginLog
     */
    public function setShowText($showText)
    {
        $this->showText = $showText;

        return $this;
    }

    /**
     * Get showText.
     *
     * @return string
     */
    public function getShowText()
    {
        return $this->showText;
    }

    /**
     * Set operation.
     *
     * @param string $operation
     *
     * @return UserLoginLog
     */
    public function setOperation($operation)
    {
        $this->operation = $operation;

        return $this;
    }

    /**
     * Get operation.
     *
     * @return string
     */
    public function getOperation()
    {
        return $this->operation;
    }

    /**
     * Set user.
     *
     * @param \AppBundle\Entity\User|null $user
     *
     * @return UserLoginLog
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set userAgent
     *
     * @param string $userAgent
     *
     * @return UserLoginLog
     */
    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;

        return $this;
    }

    /**
     * Get userAgent
     *
     * @return string
     */
    public function getUserAgent()
    {
        return $this->userAgent;
    }


    /**
     * Set url
     *
     * @param array $url
     *
     * @return UserLoginLog
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return array
     */
    public function getUrl()
    {
        return $this->url;
    }
}
