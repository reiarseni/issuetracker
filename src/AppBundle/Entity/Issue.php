<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * El Issue puede ser hecho a partir de un comment de una solution,
 * El issue es enriquecido en los comments,
 * en los comments del issue se pueden hacer preguntas
 *
 * @ORM\Entity()
 * @ORM\Table(name="issue")
 */
class Issue
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
     * Numero consecutivo del issue, se crea sin numero, lo debe asignar el sistema centralizado
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $number;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $title;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="post.blank_content")
     * @Assert\Length(min=10, minMessage="post.too_short_content")
     */
    private $content;

    //CAMPOS CLASIFICATORIOS

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Category")
     * @ORM\JoinColumn(nullable=false)
     */
    private $category;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\IssueStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\IssuePriority")
     * @ORM\JoinColumn(nullable=true)
     */
    private $priority;

    /**
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\IssueType")
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    //CAMOS DE REPORTADO POR USUARIO Y ASIGNADO A USUARIO

    /**
     * Reportado por el usuario TAL
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reportedBy;

    /**
     * Asignado al desarrollador TAL
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $assignedTo;

    //CAMPOS DE FECHAS DE RECIBIDO DEL CLIENTE Y DE DEADLINE

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", type="datetime", nullable=true)
     */
    private $receivedAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", type="datetime", nullable=true)
     */
    private $deadlineAt;

    //CAMPOS BLAMEABLE Y TIMESTAMPABLES

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @var \DateTime
     *
     * @ORM\Column(type="datetime", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $updatedBy;

    //CAMPOS DE ESTIMACION Y PROGRESO DE HORAS DE IMPLEMENTACION

    /**
     * Progreso del issue en por ciento
     *
     * @var integer
     *
     * @ORM\Column(name="progress", type="integer", nullable=true)
     */
    private $progress;

    /**
     * Horas estimadas de duracion
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $estimatedHours;

    /**
     * Horas reales de duracion
     *
     * @var string
     *
     * @ORM\Column(type="string")
     * @Assert\NotBlank
     */
    private $actualHours;

    //ONE TO MANY FIELDS

    /**
     * Los comentarios van a ser (Comentarios de Issues) o (Comentarios de Solutions)
     *
     * @var Comment[]|ArrayCollection
     *
     * @ORM\OneToMany(
     *      targetEntity="Comment",
     *      mappedBy="issue",
     *      orphanRemoval=true,
     *      cascade={"persist"}
     * )
     * -----------------------------------------------@ORM\OrderBy({"createdAt": "DESC"})
     */
    private $comments;

    /**
     * @var IssueTag[]|ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\IssueTag", cascade={"persist"})
     * @ORM\JoinTable(name="issue_tag_issue_rel")
     * @ORM\OrderBy({"name": "ASC"})
     * @Assert\Count(max="5", maxMessage="post.too_many_tags")
     */
    private $tags;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Requirement")
     * @ORM\JoinColumn(nullable=true)
     */
    private $requirement;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
        $this->comments = new ArrayCollection();
        $this->tags = new ArrayCollection();
        $this->progress = 0;
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
     * Set number.
     *
     * @param string $number
     *
     * @return Issue
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return Issue
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set estimatedHours.
     *
     * @param string $estimatedHours
     *
     * @return Issue
     */
    public function setEstimatedHours($estimatedHours)
    {
        $this->estimatedHours = $estimatedHours;

        return $this;
    }

    /**
     * Get estimatedHours.
     *
     * @return string
     */
    public function getEstimatedHours()
    {
        return $this->estimatedHours;
    }

    /**
     * Set actualHours.
     *
     * @param string $actualHours
     *
     * @return Issue
     */
    public function setActualHours($actualHours)
    {
        $this->actualHours = $actualHours;

        return $this;
    }

    /**
     * Get actualHours.
     *
     * @return string
     */
    public function getActualHours()
    {
        return $this->actualHours;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Issue
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
     * Set createdAt.
     *
     * @param \DateTime $createdAt
     *
     * @return Issue
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
     * Set status.
     *
     * @param \AppBundle\Entity\IssueStatus $status
     *
     * @return Issue
     */
    public function setStatus(\AppBundle\Entity\IssueStatus $status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return \AppBundle\Entity\IssueStatus
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set type.
     *
     * @param \AppBundle\Entity\IssueType $type
     *
     * @return Issue
     */
    public function setType(\AppBundle\Entity\IssueType $type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return \AppBundle\Entity\IssueType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set reportedBy.
     *
     * @param \AppBundle\Entity\User $reportedBy
     *
     * @return Issue
     */
    public function setReportedBy(\AppBundle\Entity\User $reportedBy)
    {
        $this->reportedBy = $reportedBy;

        return $this;
    }

    /**
     * Get reportedBy.
     *
     * @return \AppBundle\Entity\User
     */
    public function getReportedBy()
    {
        return $this->reportedBy;
    }

    /**
     * Set assignedTo.
     *
     * @param \AppBundle\Entity\User $assignedTo
     *
     * @return Issue
     */
    public function setAssignedTo(\AppBundle\Entity\User $assignedTo)
    {
        $this->assignedTo = $assignedTo;

        return $this;
    }

    /**
     * Get assignedTo.
     *
     * @return \AppBundle\Entity\User
     */
    public function getAssignedTo()
    {
        return $this->assignedTo;
    }

    /**
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Issue
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

    /**
     * Add comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Issue
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        return $this->comments->removeElement($comment);
    }

    /**
     * Get comments.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    /**
     * Add tag.
     *
     * @param \AppBundle\Entity\IssueTag $tag
     *
     * @return Issue
     */
    public function addTag(\AppBundle\Entity\IssueTag $tag)
    {
        if (!$this->tags->contains($tag)) {
            $this->tags->add($tag);
        }

        return $this;
    }

    /**
     * Remove tag.
     *
     * @param \AppBundle\Entity\IssueTag $tag
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeTag(\AppBundle\Entity\IssueTag $tag)
    {
        return $this->tags->removeElement($tag);
    }

    /**
     * Get tags.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * Set category.
     *
     * @param \AppBundle\Entity\Category $category
     *
     * @return Issue
     */
    public function setCategory(\AppBundle\Entity\Category $category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category.
     *
     * @return \AppBundle\Entity\Category
     */
    public function getCategory()
    {
        return $this->category;
    }

    public function __toString()
    {
        return  $this->getNumber() . ' - ' . $this->getTitle();
    }

    /**
     * Set priority.
     *
     * @param \AppBundle\Entity\IssuePriority $priority
     *
     * @return Issue
     */
    public function setPriority(\AppBundle\Entity\IssuePriority $priority= null)
    {
        $this->priority = $priority;

        return $this;
    }

    /**
     * Get priority.
     *
     * @return \AppBundle\Entity\IssuePriority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    /**
     * Set receivedAt
     *
     * @param \DateTime $receivedAt
     *
     * @return Issue
     */
    public function setReceivedAt($receivedAt)
    {
        $this->receivedAt = $receivedAt;

        return $this;
    }

    /**
     * Get receivedAt
     *
     * @return \DateTime
     */
    public function getReceivedAt()
    {
        return $this->receivedAt;
    }

    /**
     * Set deadlineAt
     *
     * @param \DateTime $deadlineAt
     *
     * @return Issue
     */
    public function setDeadlineAt($deadlineAt)
    {
        $this->deadlineAt = $deadlineAt;

        return $this;
    }

    /**
     * Get deadlineAt
     *
     * @return \DateTime
     */
    public function getDeadlineAt()
    {
        return $this->deadlineAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Issue
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set updatedBy
     *
     * @param \AppBundle\Entity\User $updatedBy
     *
     * @return Issue
     */
    public function setUpdatedBy(\AppBundle\Entity\User $updatedBy)
    {
        $this->updatedBy = $updatedBy;

        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return \AppBundle\Entity\User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set progress
     *
     * @param integer $progress
     *
     * @return Issue
     */
    public function setProgress($progress)
    {
        $this->progress = $progress;

        return $this;
    }

    /**
     * Get progress
     *
     * @return integer
     */
    public function getProgress()
    {
        return $this->progress;
    }

    /**
     * Set requirement
     *
     * @param \AppBundle\Entity\Requirement $requirement
     *
     * @return Issue
     */
    public function setRequirement(\AppBundle\Entity\Requirement $requirement)
    {
        $this->requirement = $requirement;

        return $this;
    }

    /**
     * Get requirement
     *
     * @return \AppBundle\Entity\Requirement
     */
    public function getRequirement()
    {
        return $this->requirement;
    }
}
