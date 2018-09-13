<?php

namespace DbAuditBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class DbAuditExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
        $loader->load('twig.yml');

        $auditSubscriber = $container->getDefinition('db_audit.event_subscriber');

        if (isset($config['audited_entities']) && !empty($config['audited_entities'])) {
            $auditSubscriber->addMethodCall('addAuditedEntities', array($config['audited_entities']));
        } else if (isset($config['unaudited_entities'])) {
            $auditSubscriber->addMethodCall('addUnauditedEntities', array($config['unaudited_entities']));
        }
    }
}
