<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="solution")
 */
class Solution
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

    /**
     * Los comentarios van a ser (Comentarios de Issues) o (Comentarios de Solutions).
     *
     * @var ArrayCollection|Comment[]
     *
     * @ORM\OneToMany(
     *     targetEntity="Comment",
     *     mappedBy="solution",
     *     orphanRemoval=true,
     *     cascade={"persist"}
     * )
     * @ORM\OrderBy({"createdAt" = "DESC"})
     */
    private $comments;

    /**
     * Si o No, el desarrollador ya lo reviso?
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $testedByDeveloper;

    /**
     * Si o No, el cliente ya lo reviso?
     *
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $checkedByClient;

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
     * Set title.
     *
     * @param string $title
     *
     * @return Solution
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
     * Set content.
     *
     * @param string $content
     *
     * @return Solution
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
     * @return Solution
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
     * Set testedByDeveloper.
     *
     * @param bool|null $testedByDeveloper
     *
     * @return Solution
     */
    public function setTestedByDeveloper($testedByDeveloper = null)
    {
        $this->testedByDeveloper = $testedByDeveloper;

        return $this;
    }

    /**
     * Get testedByDeveloper.
     *
     * @return bool|null
     */
    public function getTestedByDeveloper()
    {
        return $this->testedByDeveloper;
    }

    /**
     * Set checkedByClient.
     *
     * @param bool|null $checkedByClient
     *
     * @return Solution
     */
    public function setCheckedByClient($checkedByClient = null)
    {
        $this->checkedByClient = $checkedByClient;

        return $this;
    }

    /**
     * Get checkedByClient.
     *
     * @return bool|null
     */
    public function getCheckedByClient()
    {
        return $this->checkedByClient;
    }

    /**
     * Set createdBy.
     *
     * @param \AppBundle\Entity\User $createdBy
     *
     * @return Solution
     */
    public function setCreatedBy(User $createdBy)
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
     * @return Solution
     */
    public function addComment(Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment.
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return bool TRUE if this collection contained the specified element, FALSE otherwise
     */
    public function removeComment(Comment $comment)
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
}
