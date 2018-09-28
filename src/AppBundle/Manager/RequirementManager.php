<?php

namespace AppBundle\Manager;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Requirement;
use NotifyBundle\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class RequirementManager
 */
class RequirementManager
{
    /*** @var  ContainerInterface */
    private $container;

    /*** @var  EntityManager */
    private $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * @param Requirement $requirement
     * @return bool
     * @throws \Exception
     */
    public function crear(Requirement $requirement)
    {
        $this->em->beginTransaction();

        try {

            $requirement->setCreatedBy($this->container->get('util_manager')->getUsuarioLogeado());
            $requirement->setCreatedAt(new \DateTime());

            $this->em->persist($requirement);
            $this->em->flush();

            $this->em->commit();

            $eventDispatcher = $this->container->get('event_dispatcher');

            // When triggering an event, you can optionally pass some information.
            // For simple applications, use the GenericEvent object provided by Symfony
            // to pass some PHP variables. For more complex applications, define your
            // own event object classes.
            // See https://symfony.com/doc/current/components/event_dispatcher/generic_event.html
            $event = new GenericEvent($requirement);

            // When an event is dispatched, Symfony notifies it to all the listeners
            // and subscribers registered to it. Listeners can modify the information
            // passed in the event and they can even modify the execution flow, so
            // there's no guarantee that the rest of this controller will be executed.
            // See https://symfony.com/doc/current/components/event_dispatcher.html
            $eventDispatcher->dispatch(Events::REQUIREMENT_CREATED, $event);

            return true;

        } catch (\Exception $e) {

            $this->container->get('logger')->error(sprintf('Ocurrio error creando el requirement: Detalles %s',$e->getMessage()));

            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @param Requirement $requirement
     * @return bool
     * @throws \Exception
     */
    public function editar(Requirement $requirement)
    {
        $this->em->beginTransaction();

        try {

            $this->em->persist($requirement);
            $this->em->flush();
            $this->em->commit();

            $this->container->get('logger')->info(sprintf('El requirement fue guardado satisfactoriamente'));

            return true;

        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function crearRequirementComment(Comment $comment)
    {
        $this->em->beginTransaction();

        try {

            $comment->setCreatedBy($this->container->get('util_manager')->getUsuarioLogeado());
            $comment->setCreatedAt(new \DateTime());

            $this->em->persist($comment);
            $this->em->flush();

            $this->em->commit();

            $eventDispatcher = $this->container->get('event_dispatcher');
            $event = new GenericEvent($comment);
            $eventDispatcher->dispatch(Events::COMMENT_CREATED, $event);

            return true;

        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function editarRequirementComment(Comment $comment)
    {
        $this->em->beginTransaction();

        try {

            $this->em->persist($comment);
            $this->em->flush();

            $this->em->commit();

            return true;

        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

}
