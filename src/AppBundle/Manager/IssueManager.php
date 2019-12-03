<?php

declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use AppBundle\Entity\IssueStatus;
use Doctrine\ORM\EntityManager;
use NotifyBundle\Events;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

/**
 * Class IssueManager.
 */
class IssueManager
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
     * @param Issue $issue
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function crear(Issue $issue)
    {
        $this->em->beginTransaction();

        try {
            $issue->setCreatedBy($this->container->get('util_manager')->getUsuarioLogeado());
            $issue->setCreatedAt(new \DateTime());

            //$usuario->addRole($rol);

            $this->em->persist($issue);
            $this->em->flush();

            $this->em->commit();

            $eventDispatcher = $this->container->get('event_dispatcher');

            // When triggering an event, you can optionally pass some information.
            // For simple applications, use the GenericEvent object provided by Symfony
            // to pass some PHP variables. For more complex applications, define your
            // own event object classes.
            // See https://symfony.com/doc/current/components/event_dispatcher/generic_event.html
            $event = new GenericEvent($issue);

            // When an event is dispatched, Symfony notifies it to all the listeners
            // and subscribers registered to it. Listeners can modify the information
            // passed in the event and they can even modify the execution flow, so
            // there's no guarantee that the rest of this controller will be executed.
            // See https://symfony.com/doc/current/components/event_dispatcher.html
            $eventDispatcher->dispatch(Events::ISSUE_CREATED, $event);

            return true;
        } catch (\Exception $e) {
            $this->container->get('logger')->error(sprintf('Ocurrio error creando el issue: Detalles %s', $e->getMessage()));

            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @param Issue $issue
     * @param mixed $statusOld
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function editar(Issue $issue, $statusOld)
    {
        $this->em->beginTransaction();

        try {
            $this->em->persist($issue);
            $this->em->flush();
            $this->em->commit();

            // @var IssueStatus $statusOld
            if ($statusOld && $issue->getStatus()->getId() != $statusOld->getId()) {
                $eventDispatcher = $this->container->get('event_dispatcher');
                $event = new GenericEvent($issue);
                $eventDispatcher->dispatch(Events::ISSUE_STATUS_CHANGED, $event);
            }

            $this->container->get('logger')->info(sprintf('El issue fue guardado satisfactoriamente'));

            return true;
        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

    /**
     * @param Comment $comment
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function crearComment(Comment $comment)
    {
        $this->em->beginTransaction();

        try {
            $comment->setCreatedBy($this->container->get('util_manager')->getUsuarioLogeado());
            $comment->setCreatedAt(new \DateTime());

            //$usuario->addRole($rol);

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
     * @param Comment $comment
     *
     * @throws \Exception
     *
     * @return bool
     */
    public function editarComment(Comment $comment)
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
