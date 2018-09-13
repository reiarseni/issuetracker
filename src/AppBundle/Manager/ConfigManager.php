<?php

namespace AppBundle\Manager;

use AppBundle\Form\Model\ConfigModel;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\ORM\EntityManager;
/**
 * Class ConfigManager
 */
class ConfigManager
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
     * @param ConfigModel $configModel
     * @return bool
     * @throws \Exception
     */
    public function editar(ConfigModel $configModel)
    {
        $this->em->beginTransaction();

        try {

            $repoConfig = $this->em->getRepository('AppBundle:Config');

            $repoConfig->updateValue('app.nombre',$configModel->getNombre());
            $repoConfig->updateValue('app.email',$configModel->getEmail());
            $repoConfig->updateValue('social.facebook',$configModel->getFacebookUrl());
            $repoConfig->updateValue('app.mostrar_contacto',$configModel->isMostrarContacto());
            $repoConfig->updateValue('app.en_mantenimiento',$configModel->isEnMantenimiento());

            $this->em->flush();

            $this->em->commit();

            return true;

        } catch (\Exception $e) {
            $this->em->rollback();
            throw $e;
        }
    }

}
