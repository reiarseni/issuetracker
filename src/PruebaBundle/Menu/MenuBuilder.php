<?php

declare(strict_types=1);

namespace PruebaBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class MenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @param FactoryInterface $factory
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function top(FactoryInterface $factory)
    {
        $menu = $factory->createItem('root');
        $menu->setChildrenAttribute('class', 'nav navbar-nav pull-right');

        $child = function ($label, $route) use ($menu) {
            $attributes = ['role' => 'presentation'];
            $menu->addChild($label, compact('route', 'attributes'));
        };

        $child('Projects', 'projects');

        return $menu;
    }

    /**
     * @return UserInterface
     */
    private function getUser()
    {
        $token = $this->container->get('security.token_storage')->getToken();
        if (!$token instanceof TokenInterface) {
            return null;
        }

        return $token->getUser();
    }
}
