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
                ->scalarNode('event_store_address')->isRequired()->end()
                ->scalarNode('basket_projection')->isRequired()->end()
            ->end();

        return $treeBuilder;
    }
}
