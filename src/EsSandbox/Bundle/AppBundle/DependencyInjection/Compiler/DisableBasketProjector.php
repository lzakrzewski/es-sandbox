<?php

namespace EsSandbox\Bundle\AppBundle\DependencyInjection\Compiler;

use EsSandbox\Basket\Infrastructure\Projection\NullBasketProjector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class DisableBasketProjector implements CompilerPassInterface
{
    /** {@inheritdoc} */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('es_sandbox.basket.projector.is_enabled')) {
            return;
        }

        if ($container->getParameter('es_sandbox.basket.projector.is_enabled')) {
            return;
        }

        $projectorDefinition = $container->getDefinition('es_sandbox.projector.basket.mysql');
        $projectorDefinition->setClass(NullBasketProjector::class);
    }
}
