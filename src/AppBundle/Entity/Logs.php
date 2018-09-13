<?php

namespace AppBundle\Entity;

use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="logs")
 * @ORM\Entity(repositoryClass="Gedmo\Loggable\Entity\Repository\LogEntryRepository")
 *
 */
class Logs extends AbstractLogEntry
{
}
