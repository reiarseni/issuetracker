<?php

namespace AppBundle\Twig;

use AppBundle\Entity\Config;
use Doctrine\ORM\EntityManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UtilExtension extends \Twig_Extension {

    /*** @var ContainerInterface */
    protected $container;

    /*** @var EntityManager */
    protected $em;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('util', array($this, 'getUtil')),
            new \Twig_SimpleFunction('config', array($this, 'getConfig'))
        );
    }

    public function getUtil($clave = null)
    {
        if ($clave=='usuario.rol') {
           return $this->container->get('util_manager')->getRol();
        }
    }

    public function getConfig($clave = null)
    {
        $config = $this->em->getRepository('AppBundle:Config')->findAll();
        $result= [];
        foreach ($config as $cf) {
            /*** @var Config $cf */
            $result[$cf->getTheKey()] = $cf->getTheValue();
        }
        return  $result[$clave];
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'util_extension';
    }


}