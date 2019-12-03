<?php

declare(strict_types=1);

namespace AppBundle\Doctrine;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AbstractIdGenerator;

/**
 * UUID generator for the Doctrine ORM.
 */
class UuidGenerator extends AbstractIdGenerator
{
    /**
     * Generate an identifier.
     *
     * @param \Doctrine\ORM\EntityManager  $em
     * @param \Doctrine\ORM\Mapping\Entity $entity
     *
     * @return string
     */
    public function generate(EntityManager $em, $entity)
    {
        $orderedTimePart = $this->udate('Ymd-His-u');

        $randPart = $this->mt_rand_str(7, '0123456789ABCDEF');

        return $orderedTimePart.'-'.$randPart;
    }

    /**
     * @param string $format
     * @param null   $utimestamp
     *
     * @return false|string
     */
    private function udate($format = 'u', $utimestamp = null)
    {
        $utimestamp = microtime(true);
        $timestamp = floor($utimestamp);
        $milliseconds = round(($utimestamp - $timestamp) * 1000000);

        while (\strlen((string) $milliseconds) < 6) {
            $milliseconds = '0'.$milliseconds;
        }

        return date(preg_replace('`(?<!\\\\)u`', $milliseconds, $format), (int) $timestamp);
    }

    /**
     * @param $l
     * @param string $c
     *
     * @return string
     */
    private function mt_rand_str($l, $c = 'abcdefghijklmnopqrstuvwxyz1234567890')
    {
        for ($s = '', $cl = \strlen($c) - 1, $i = 0; $i < $l; $s .= $c[mt_rand(0, $cl)], ++$i);

        return $s;
    }
}
