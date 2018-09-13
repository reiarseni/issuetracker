<?php

namespace AppBundle\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ConfigRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ConfigRepository extends EntityRepository
{
    public function updateValue($key, $value)
    {
        $qB = $this->getEntityManager()->createQueryBuilder()
            ->update('AppBundle:Config', 'c')
            ->set('c.theValue', '?1')
            ->where('c.theKey = ?2')
            ->setParameter(1, $value)
            ->setParameter(2, $key);

        return $qB->getQuery()->execute();
    }

    public function getValue($key)
    {
        $qB = $this->getEntityManager()->createQueryBuilder()
            ->select('c.theValue')
            ->from('AppBundle:Config', 'c')
            ->where('c.theKey = ?1')
            ->setParameter(1, $key);

        $resultado = $qB->getQuery()->getOneOrNullResult();

        if ($this->count($resultado) > 0) {
            return $resultado['theValue'];
        }

        return null;
    }
}