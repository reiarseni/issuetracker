<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * los comments enriquecen los issues y las solutions
 * pueden ser de tipo NORMAL, ACLARACION o tipo PREGUNTA
 * siempre relacionados con el issue padre o la solution padre
 *
 * Class Comment
 * @package AppBundle\Entity
 * @ORM\Table(name="comment")
 * @ORM\Entity()
 */
class Comment
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
     * Tipo de commentario pueden ser de tipo OBSERVACION, ACLARACION o tipo PREGUNTA
     *
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $commentType;

    /**
     * Si esta establecido es un comentario de un Issue
     *
     * @var Issue
     *
     * @ORM\ManyToOne(targetEntity="Issue", inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $issue;

    /**
     * Si esta establecido es un comentario de una Solution
     *
     * @var Solution
     *
     * @ORM\ManyToOne(targetEntity="Solution", inversedBy="comments")
     * @ORM\JoinColumn(nullable=true)
     */
    private $solution;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="comment.blank")
     * @Assert\Length(
     *     min=5,
     *     minMessage="comment.too_short",
     *     max=20000,
     *     maxMessage="comment.too_long"
     * )
     */
    private $content;

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

    //trait de CreatedBy, CreatedAt, UpdatedBy, UpdatedAt, InactivatedBy, InactivatedAt

    public function __construct()
    {
        $this->commentType ='OBSERVACION';
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
     * Set commentType.
     *
     * @param string $commentType
     *
     * @return Comment
     */
    public function setCommentType($commentType)
    {
        $this->commentType = $commentType;

        return $this;
    }

    /**
     * Get commentType.
     *
     * @return string
     */
    public function getCommentType()
    {
        return $this->commentType;
    }

    /**
     * Set content.
     *
     * @param string $content
     *
     * @return Comment
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
     * Set issue.
     *
     * @param \AppBundle\Entity\Issue $issue
     *
     * @return Comment
     */
    public function setIssue(\AppBundle\Entity\Issue $issue)
    {
        $this->issue = $issue;

        return $this;
    }

    /**
     * Get issue.
     *
     * @return \AppBundle\Entity\Issue
     */
    public function getIssue()
    {
        return $this->issue;
    }

    /**
     * Set solution.
     *
     * @param \AppBundle\Entity\Solution $solution
     *
     * @return Comment
     */
    public function setSolution(\AppBundle\Entity\Solution $solution)
    {
        $this->solution = $solution;

        return $this;
    }

    /**
     * Get solution.
     *
     * @return \AppBundle\Entity\Solution
     */
    public function getSolution()
    {
        return $this->solution;
    }

    /**
     * Set createdAt.
     *
     * @param \DateTime|null $createdAt
     *
     * @return Comment
     */
    public function setCreatedAt($createdAt = null)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt.
     *
     * @return \DateTime|null
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
     * @return Comment
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

    public function __toString()
    {
        return  $this->getId() . ' - ' . $this->getCommentType();
    }
}
