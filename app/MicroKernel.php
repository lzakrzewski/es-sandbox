<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class MicroKernel extends Kernel
{
    use MicroKernelTrait;

    /** {@inheritdoc} */
    public function registerBundles()
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new SimpleBus\SymfonyBridge\SimpleBusCommandBusBundle(),
            new SimpleBus\SymfonyBridge\SimpleBusEventBusBundle(),
            new EsSandbox\Bundle\AppBundle\AppBundle(),
        ];
    }

    /** {@inheritdoc} */
    public function getCacheDir()
    {
        return __DIR__.'/../var/cache';
    }

    /** {@inheritdoc} */
    public function getLogDir()
    {
        return __DIR__.'/../var/logs';
    }

    /** {@inheritdoc} */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
    }

    /** {@inheritdoc} */
    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}