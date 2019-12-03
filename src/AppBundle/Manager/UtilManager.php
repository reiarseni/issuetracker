<?php

declare(strict_types=1);

namespace AppBundle\Manager;

use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class UsuarioManager.
 */
class UtilManager
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
     * @return string|User|null
     */
    public function getUsuarioLogeado()
    {
        $tokenStorage = $this->container->get('security.token_storage', ContainerInterface::NULL_ON_INVALID_REFERENCE)->getToken();

        $usuario = null;

        if (null != $tokenStorage) {
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
            $rol = self::getRolTraducido($rolRaw);
            if ($rol) {
                break;
            }
        }

        return $rol;
    }

    public static function getRolTraducido($rol)
    {
        if ('ROLE_SUPER_ADMIN' == $rol) {
            $rol = 'SuperAdmin';
        }
        if ('ROLE_ADMIN' == $rol) {
            $rol = 'Admin';
        }

        return $rol;
    }
}
