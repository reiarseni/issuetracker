<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Cada vez que se hace una actualizacion se entrega un changelog al cliente
 * un changelog va desde una fecha a otra en cuanto a development
 * tiene un dia de actualizado en produccion o testing
 * tiene un grupo de solutions
 * antes de actualizar puede que se ponga un Tagname al ultimo commit en git
 *
 * @ORM\Entity()
 * @ORM\Table(name="changelog")
 */
class Changelog
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
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="post.blank_content")
     * @Assert\Length(min=10, minMessage="post.too_short_content")
     */
    private $content;

    /**
     * TagName en Git de la actualizacion, ejemplo v0.1.1
     *
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $tagName;

    /**
     * Fecha del primer commit(solution) relacionado
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $dateStart;

    /**
     *
     * Fecha del ultimo commit(solution) relacionado
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $dateEnd;

    /**
     * Fecha en que fue actualizado en produccion
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $dateDeployed;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
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
     * Set content.
     *
     * @param string $content
     *
     * @return Changelog
     */
    public function setContent($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Get content.
     *
     * @return string
     */
    public function getContent()
    {
        return $this->content;
    }

    /**
     * Set tagName.
     *
     * @param string $tagName
     *
     * @return Changelog
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;

        return $this;
    }

    /**
     * Get tagName.
     *
     * @return string
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * Set dateStart.
     *
     * @param \DateTime $dateStart
     *
     * @return Changelog
     */
    public function setDateStart($dateStart)
    {
        $this->dateStart = $dateStart;

        return $this;
    }

    /**
     * Get dateStart.
     *
     * @return \DateTime
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * Set dateEnd.
     *
     * @param \DateTime $dateEnd
     *
     * @return Changelog
     */
    public function setDateEnd($dateEnd)
    {
        $this->dateEnd = $dateEnd;

        return $this;
    }

    /**
     * Get dateEnd.
     *
     * @return \DateTime
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * Set dateDeployed.
     *
     * @param \DateTime $dateDeployed
     *
     * @return Changelog
     */
    public function setDateDeployed($dateDeployed)
    {
        $this->dateDeployed = $dateDeployed;

        return $this;
    }

    /**
     * Get dateDeployed.
     *
     * @return \DateTime
     */
    public function getDateDeployed()
    {
        return $this->dateDeployed;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Changelog
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
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Changelog
     */
    public function setCreatedBy(\AppBundle\Entity\User $createdBy)
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    /**
     * Get createdBy.
     *
     * @return \AppBundle\Entity\User
     */
    public function getCreatedBy()
    {
        return $this->createdBy;
    }
}
