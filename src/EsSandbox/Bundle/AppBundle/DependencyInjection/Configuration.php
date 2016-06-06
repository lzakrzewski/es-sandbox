<?php

namespace EsSandbox\Bundle\AppBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /** {@inheritdoc} */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $root        = $treeBuilder->root('es_sandbox');

        $this->applyEventStoreConfiguration($root);
        $this->applyProjectionConfiguration($root);

        return $treeBuilder;
    }

    private function applyEventStoreConfiguration(ArrayNodeDefinition $root)
    {
        $root
            ->children()
                ->scalarNode('event_store_host')->isRequired()->end()
                ->scalarNode('event_store_port')->isRequired()->end()
                ->scalarNode('event_store_user')->isRequired()->end()
                ->scalarNode('event_store_password')->isRequired()->end()
            ->end()
        ;
    }

    private function applyProjectionConfiguration(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('basket')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('projector')
                        ->addDefaultsIfNotSet()
                            ->children()
                                ->scalarNode('is_enabled')->defaultTrue()->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
