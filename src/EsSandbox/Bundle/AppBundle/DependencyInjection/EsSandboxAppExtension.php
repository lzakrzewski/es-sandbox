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
        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/Common'));
        $loader->load('command_bus.yml');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config/Basket'));
        $loader->load('command_handlers.yml');

        $configuration = new Configuration();
        $this->processConfiguration($configuration, $config);
    }

    /** {@inheritdoc} */
    public function getAlias()
    {
        return 'es_sandbox';
    }
}
