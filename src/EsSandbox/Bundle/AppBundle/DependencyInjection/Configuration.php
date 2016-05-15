<?php

namespace EsSandbox\Bundle\AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /** {@inheritdoc} */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root        = $treeBuilder->root('es_sandbox');

        $root
            ->children()
                ->scalarNode('event_store_host')->isRequired()->end()
                ->scalarNode('event_store_port')->isRequired()->end()
                ->scalarNode('event_store_user')->defaultValue('default')->end()
                ->scalarNode('event_store_password')->defaultValue('default')->end()
            ->end();

        return $treeBuilder;
    }
}
