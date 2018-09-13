<?php

namespace NotifyBundle\EventListener;

use FOS\UserBundle\Event\UserEvent;
use NotifyBundle\Entity\UserLoginLog;
use Symfony\Component\Security\Core\Event\AuthenticationFailureEvent;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Custom login listener.
 */
class LoginListener
{

    private $container;

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Do the magic.
     * @param InteractiveLoginEvent $event
     */
    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $em = $this->container->get('doctrine')->getManager();
        $usuario = $event->getAuthenticationToken()->getUser();
        $name = $event->getAuthenticationToken()->getUsername();
        $log = new UserLoginLog();

        $request = $this->container->get('request_stack')->getCurrentRequest();
        $log->setIpAddress($request->getClientIp());
        $reflexion = new \ReflectionClass('Symfony\Component\HttpFoundation\Request');
        $property = $meta = $reflexion->getProperty('headers');
        $userAgent = $property->getValue($request)->get('user_agent');
        $log->setUserAgent($userAgent);

        $log->setShowText("Se inició la sesión del usuario {$name}.");
        $log->setOperation('Inicio de sesión');

        $log->setUser($usuario);
        $log->setUsername($name);

        $log->setCreatedAt(new \DateTime());

        $em->persist($log);
        $em->flush();
    }


    public function onAuthenticationFailure(AuthenticationFailureEvent $event)
    {
        $em = $this->container->get('doctrine')->getManager();
        $name = substr($event->getAuthenticationToken()->getUsername(), 0, 20);
        $log = new UserLoginLog();

        $request = $this->container->get('request_stack')->getCurrentRequest();
        $log->setIpAddress($request->getClientIp());
        $reflexion = new \ReflectionClass('Symfony\Component\HttpFoundation\Request');
        $property = $meta = $reflexion->getProperty('headers');
        $userAgent = $property->getValue($request)->get('user_agent');
        $log->setUserAgent($userAgent);

        $log->setShowText("Fallo el inició de sesión del usuario {$name}.");
        $log->setOperation('Fallo de inicio de sesión');

        $log->setUser(null);
        $log->setUsername($name);

        $log->setCreatedAt(new \DateTime());

        $em->persist($log);
        $em->flush();
    }

    #estos dos no funcionan

    public function onUserCreated(UserEvent $event)
    {
        $em = $this->container->get('doctrine')->getManager();
        $name = substr($event->getUser()->getUsername(), 0, 20);
        $log = new UserLoginLog();
        $log->setIpAddress($this->container->get('request_stack')->getCurrentRequest()->getClientIp());
        $log->setShowText("Se creo el usuario {$name}.");
        $log->setOperation('Creacion de usuario');

        $log->setUser($event->getUser());
        $log->setUsername($name);

        $log->setCreatedAt(new \DateTime());

        $em->persist($log);
        $em->flush();
    }

    public function onPasswordChanged(UserEvent $event)
    {
        $em = $this->container->get('doctrine')->getManager();
        $name = substr($event->getUser()->getUsername(), 0, 20);
        $log = new UserLoginLog();
        $log->setIpAddress($this->container->get('request_stack')->getCurrentRequest()->getClientIp());
        $log->setShowText("Cambio de password el usuario {$name}.");
        $log->setOperation('Cambio de Password');

        $log->setUser($event->getUser());
        $log->setUsername($name);

        $log->setCreatedAt(new \DateTime());

        $em->persist($log);
        $em->flush();
    }


}