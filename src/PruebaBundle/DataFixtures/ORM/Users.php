<?php

declare(strict_types=1);

namespace PruebaBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use PruebaBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Users implements ContainerAwareInterface, FixtureInterface, OrderedFixtureInterface
{
    private $container;

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 0;
    }

    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em)
    {
        $users = ['yoda', 'luke'];
        foreach ($users as $username) {
            $user = new User();
            $user->setFirstname(ucfirst($username));
            $user->setUsername($username);

            $passwd = $this->container->get('security.password_encoder')->encodePassword($user, 'secret');
            $user->setPassword($passwd);

            $em->persist($user);
        }
        $em->flush();
    }
}
