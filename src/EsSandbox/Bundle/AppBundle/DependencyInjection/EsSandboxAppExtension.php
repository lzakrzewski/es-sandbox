<?php

namespace EsSandbox\Bundle\AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader;

class EsSandboxAppExtension extends Extension
{
    /** {@inheritdoc} */
    public function load(array $config, ContainerBuilder $container)
    {
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('command_components.yml');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/Common'));
        $loader->load('command_bus.yml');
        $loader->load('event_store.yml');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/Basket'));
        $loader->load('command_handlers.yml');
        $loader->load('projections.yml');
        $loader->load('event_store.yml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);

        $container->setParameter('es_sandbox.event_store.host', $config['event_store_host']);
        $container->setParameter('es_sandbox.event_store.port', $config['event_store_port']);
        $container->setParameter('es_sandbox.event_store.user', $config['event_store_user']);
        $container->setParameter('es_sandbox.event_store.password', $config['event_store_password']);
    }

    /** {@inheritdoc} */
    public function getAlias()
    {
        return 'es_sandbox';
    }
}
