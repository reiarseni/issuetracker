<?php

declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use NotifyBundle\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class UsuarioManager.
 */
class UsuarioManager
{
    // @var  ContainerInterface
    private $container;

    // @var  EntityManager
    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param User $usuario
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function crear(User $usuario)
    {
        $this->em->beginTransaction();

        try {
            //$usuario->addRole($rol);

            $this->em->persist($usuario);
            $this->em->flush();

            $this->em->commit();

//          $eventDispatcher = $this->container->get('event_dispatcher');
//          $event = new GenericEvent($usuario);
//          $eventDispatcher->dispatch(Events::ISSUE_CREATED, $event);

            return true;
        } catch (\Exception $e) {
            dump($e);
            die;

            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @param User $usuario
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function editar(User $usuario)
    {
        $this->em->beginTransaction();

        try {
            $this->em->persist($usuario);
            $this->em->flush();

            $this->em->commit();

            return true;
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }
}
