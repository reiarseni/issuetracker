<?php

namespace NotifyBundle\Entity;

use AppBundle\Entity\User;
use Doctrine\ORM\Mapping as ORM;

/**
 * UserNotification
 *
 * @ORM\Table(name="user_notification")
 * @ORM\Entity
 */
class UserNotification
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="message", type="string", length=220, nullable=false)
     */
    private $message;

    /**
     * @var string
     *
     * @ORM\Column(name="action_uri", type="string", length=255, nullable=false)
     */
    private $actionUri;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="read_at", type="datetime", nullable=true)
     */
    private $readAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="recipient_id", referencedColumnName="id")
     * })
     */
    private $recipient;


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
     * Set message.
     *
     * @param string $message
     *
     * @return UserNotification
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message.
     *
     * @return string
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set actionUri.
     *
     * @param string $actionUri
     *
     * @return UserNotification
     */
    public function setActionUri($actionUri)
    {
        $this->actionUri = $actionUri;

        return $this;
    }

    /**
     * Get actionUri.
     *
     * @return string
     */
    public function getActionUri()
    {
        return $this->actionUri;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return UserNotification
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
     * Set readAt.
     *
     * @param \DateTime|null $readAt
     *
     * @return UserNotification
     */
    public function setReadAt($readAt = null)
    {
        $this->readAt = $readAt;

        return $this;
    }

    /**
     * Get readAt.
     *
     * @return \DateTime|null
     */
    public function getReadAt()
    {
        return $this->readAt;
    }

    /**
     * Set recipient.
     *
     * @param \AppBundle\Entity\User|null $recipient
     *
     * @return UserNotification
     */
    public function setRecipient(\AppBundle\Entity\User $recipient = null)
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * Get recipient.
     *
     * @return \AppBundle\Entity\User|null
     */
    public function getRecipient()
    {
        return $this->recipient;
    }
}
