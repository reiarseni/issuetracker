<?php

declare(strict_types=1);

namespace DbAuditBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

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
            $auditSubscriber->addMethodCall('addAuditedEntities', [$config['audited_entities']]);
        } elseif (isset($config['unaudited_entities'])) {
            $auditSubscriber->addMethodCall('addUnauditedEntities', [$config['unaudited_entities']]);
        }
    }
}
