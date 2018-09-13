<?php

namespace AppBundle\Entity\Repository;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository
{
    public function filter(User $user, $searchParams = array())
    {
    }

}
