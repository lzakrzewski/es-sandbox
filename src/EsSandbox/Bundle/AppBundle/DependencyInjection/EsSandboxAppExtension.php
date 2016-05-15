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
        $loader->load('guzzle_http.yml');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/Common'));
        $loader->load('command_bus.yml');
        $loader->load('event_store.yml');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/Basket'));
        $loader->load('command_handlers.yml');
        $loader->load('projections.yml');
        $loader->load('event_store.yml');

        $configuration = new Configuration();
        $config        = $this->processConfiguration($configuration, $config);

        $container->setParameter('es_sandbox.event_store.uri', $this->uri($config));
        $container->setParameter('es_sandbox.event_store.auth', $this->auth($config));
        $container->setParameter('es_sandbox.basket.projector.is_enabled', $config['basket']['projector']['is_enabled']);
    }

    /** {@inheritdoc} */
    public function getAlias()
    {
        return 'es_sandbox';
    }

    private function uri(array $config)
    {
        return sprintf('%s:%s', $config['event_store_host'], $config['event_store_port']);
    }

    private function auth(array $config)
    {
        return [$config['event_store_user'], $config['event_store_password']];
    }
}
