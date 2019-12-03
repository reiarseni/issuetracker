<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use AppBundle\Manager\UtilManager;
use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * User.
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 * @UniqueEntity(fields={"username"}, message="validation.user.recurrent")
 * @UniqueEntity(fields={"email"}, message="validation.email.recurrent")
 */
class User extends BaseUser
{
    /**
     * @var string
     *
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class="AppBundle\Doctrine\UuidGenerator")
     */
    protected $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="expires_at", type="datetime", nullable=true)
     */
    private $expiresAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="expired", type="boolean", nullable=true)
     */
    private $expired;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="credentials_expire_at", type="datetime", nullable=true)
     */
    private $credentialsExpireAt;

    /**
     * @var bool
     *
     * @ORM\Column(name="credentials_expired", type="boolean", nullable=true)
     */
    private $credentialsExpired;

    /**
     * @var bool
     *
     * @ORM\Column(name="locked", type="boolean", nullable=true)
     */
    private $locked;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="last_activity", type="datetime", nullable=true)
     */
    private $lastActivity;

    /**
     * Apikey que se genera para el usuario, los administradores son los que generan/regeneran apikey para los demas usuarios.
     *
     * @var string
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private $apiKey;

    /**
     * Constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return $this->getUsername();
    }

    /**
     * Set expired.
     *
     * @param bool $expired
     *
     * @return User
     */
    public function setExpired($expired)
    {
        $this->expired = $expired;

        return $this;
    }

    /**
     * Get expired.
     *
     * @return bool
     */
    public function getExpired()
    {
        return $this->expired;
    }

    /**
     * Set credentialsExpired.
     *
     * @param bool $credentialsExpired
     *
     * @return User
     */
    public function setCredentialsExpired($credentialsExpired)
    {
        $this->credentialsExpired = $credentialsExpired;

        return $this;
    }

    /**
     * Get credentialsExpired.
     *
     * @return bool
     */
    public function getCredentialsExpired()
    {
        return $this->credentialsExpired;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return User
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
     * Set updatedAt.
     *
     * @param \DateTime $updatedAt
     *
     * @return User
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set lastActivity.
     *
     * @param \DateTime $lastActivity
     *
     * @return User
     */
    public function setLastActivity($lastActivity)
    {
        $this->lastActivity = $lastActivity;

        return $this;
    }

    /**
     * Get lastActivity.
     *
     * @return \DateTime
     */
    public function getLastActivity()
    {
        return $this->lastActivity;
    }

    /**
     * Set expiresAt.
     *
     * @param \DateTime $expiresAt
     *
     * @return User
     */
    public function setExpiresAt($expiresAt)
    {
        $this->expiresAt = $expiresAt;

        return $this;
    }

    /**
     * Get expiresAt.
     *
     * @return \DateTime
     */
    public function getExpiresAt()
    {
        return $this->expiresAt;
    }

    /**
     * Set credentialsExpireAt.
     *
     * @param \DateTime $credentialsExpireAt
     *
     * @return User
     */
    public function setCredentialsExpireAt($credentialsExpireAt)
    {
        $this->credentialsExpireAt = $credentialsExpireAt;

        return $this;
    }

    /**
     * Get credentialsExpireAt.
     *
     * @return \DateTime
     */
    public function getCredentialsExpireAt()
    {
        return $this->credentialsExpireAt;
    }

    /**
     * Set locked.
     *
     * @param bool $locked
     *
     * @return User
     */
    public function setLocked($locked)
    {
        $this->locked = $locked;

        return $this;
    }

    /**
     * Get locked.
     *
     * @return bool
     */
    public function getLocked()
    {
        return $this->locked;
    }

    public function rolesToString()
    {
        $roles = $this->roles;
        $roles_string = '';
        if (0 == \count($roles)) {
            return ' Ninguno asignado';
        }
        for ($i = 0; $i < \count($roles); ++$i) {
            if (1 == \count($roles) - $i) {
                $roles_string .= $roles[$i];

                return $roles_string;
            }
            $roles_string .= $roles[$i].' - ';
        }
    }

    public function getRolTraducido()
    {
        $roles = $this->getRoles();

        $rol = 'Rol Desconocido';

        foreach ($roles as $rolRaw) {
            $rol = UtilManager::getRolTraducido($rolRaw);
            if ($rol) {
                break;
            }
        }

        return $rol;
    }

    /**
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return User
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get apiKey.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }
}
