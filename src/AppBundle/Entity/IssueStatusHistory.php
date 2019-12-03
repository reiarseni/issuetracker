<?php

declare(strict_types=1);

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Lleva el historial de cambios de los estados de los issues.
 *
 * @ORM\Entity
 * @ORM\Table(name="issue_status_history")
 */
class IssueStatusHistory
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Issue")
     * @ORM\JoinColumn(nullable=false)
     */
    private $issue;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\IssueStatus")
     * @ORM\JoinColumn(nullable=false)
     */
    private $status;

    /**
     * Fecha en la que se hizo el cambio de estado.
     *
     * @var \DateTime
     *
     * @ORM\Column(type="datetime")
     * @Assert\DateTime
     */
    private $changedAt;

    /**
     * Usuario quien hizo el cambio de estado.
     *
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $changedBy;

    public function __construct()
    {
    }
}
