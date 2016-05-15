<?php

namespace EsSandbox\Bundle\AppBundle;

use EsSandbox\Bundle\AppBundle\DependencyInjection\Compiler\DisableBasketProjector;
use EsSandbox\Bundle\AppBundle\DependencyInjection\EsSandboxAppExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AppBundle extends Bundle
{
    /** {@inheritdoc} */
    public function getContainerExtension()
    {
        return new EsSandboxAppExtension();
    }

    /** {@inheritdoc} */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DisableBasketProjector());
    }
}
