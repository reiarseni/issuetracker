<?php

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;

/**
 * Class UsuarioManager
 */
class UtilManager
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
     * @return null|User|string
     */
    public function getUsuarioLogeado()
    {
        $tokenStorage = $this->container->get('security.token_storage', ContainerInterface::NULL_ON_INVALID_REFERENCE)->getToken();

        $usuario = null;

        if ($tokenStorage != null) {
            $usuario = $tokenStorage->getUser();
        }

        return $usuario;
    }

    public function getRol()
    {
        $rol = null;
        $usuario = $this->getUsuarioLogeado();

        $roles = $usuario->getRoles();

        foreach ($roles as $rolRaw) {
            $rol = UtilManager::getRolTraducido($rolRaw);
            if ($rol) {
                break;
            }
        }

        return $rol;
    }

    public static function getRolTraducido($rol)
    {

        if ($rol == 'ROLE_SUPER_ADMIN') {
            $rol = 'SuperAdmin';
        }
        if ($rol == 'ROLE_ADMIN') {
            $rol = 'Admin';
        }

        return $rol;
    }
}
