<?php

namespace NotifyBundle\EventListener;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Issue;
use Doctrine\ORM\EntityManager;
use NotifyBundle\Entity\UserLoginLog;
use NotifyBundle\Events;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class NotifySubscriber implements EventSubscriberInterface
{

    /*** @var EntityManager */
    private $em;

    private $urlGenerator;

    public function __construct( EntityManager $em, UrlGeneratorInterface $urlGenerator)
    {
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents()
    {
        return [
            Events::ISSUE_CREATED => 'onIssueCreated',
            Events::COMMENT_CREATED => 'onCommentCreated',
            Events::ISSUE_STATUS_CHANGED => 'onStatusChanged',
        ];
    }

    public function onIssueCreated(GenericEvent $event)
    {
        /*** @var Issue $issue */
        $issue = $event->getSubject();
        $userName = $issue->getCreatedBy()->getUsername();
        $log = new UserLoginLog();

        $log->setShowText("Se Creo el issue {$userName}.");
        $log->setOperation('Nuevo Issue');

        $log->setUser($issue->getCreatedBy());
        $log->setUsername($userName);

        $log->setCreatedAt($issue->getCreatedAt());

        /*$linkToPost = $this->urlGenerator->generate('issue_show', [
            'id' => $issue->getId()
        ], UrlGeneratorInterface::RELATIVE_PATH);*/

        $url =array('name'=>'issue_show','params'=>array('id'=>$issue->getId()));
        $log->setUrl(  $url );

        $this->em->persist($log);
        $this->em->flush();

    }

    public function onCommentCreated(GenericEvent $event)
    {
        /*** @var Comment $comment */
        $comment = $event->getSubject();
        $issue = $comment->getIssue();
        $userName = $issue->getCreatedBy()->getUsername();
        $log = new UserLoginLog();

        $log->setShowText("Se Creo el Comentario");
        $log->setOperation('Nuevo Comentario');

        $log->setUser($issue->getCreatedBy());
        $log->setUsername($userName);

        $log->setCreatedAt(new \DateTime());

        $url =array('name'=>'issue_show','params'=>array('id'=>$issue->getId()));
        $log->setUrl( $url );

        $this->em->persist($log);
        $this->em->flush();
    }

    public function onStatusChanged(GenericEvent $event)
    {
        /*** @var Issue $issue */
        $issue = $event->getSubject();
        $userName = $issue->getCreatedBy()->getUsername();
        $log = new UserLoginLog();

        $log->setShowText(sprintf("Issue [%s] Cambio Estado [%s]", $issue->getTitle(),$issue->getStatus()));
        $log->setOperation('Issue Cambio Estado');

        $log->setUser($issue->getCreatedBy());
        $log->setUsername($userName);

        $log->setCreatedAt(new \DateTime());

        $url =array('name'=>'issue_show','params'=>array('id'=>$issue->getId()));
        $log->setUrl( $url );

        $this->em->persist($log);
        $this->em->flush();

    }
}
