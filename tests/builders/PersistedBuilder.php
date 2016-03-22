<?php

namespace tests\builders;

use Symfony\Component\DependencyInjection\ContainerInterface;

final class PersistedBuilder implements Builder
{
    /** @var ContainerInterface */
    private $container;

    /** @var PersistentBuilder */
    private $persistentBuilder;

    public function __construct(ContainerInterface $container, PersistentBuilder $builder)
    {
        $this->container         = $container;
        $this->persistentBuilder = $builder;
    }

    public function build()
    {
        $built = $this->persistentBuilder->build();

        $this->persistentBuilder->persist($this->container, $built);

        return $built;
    }

    public function __call($name, array $arguments)
    {
        return new self($this->container, call_user_func_array([$this->persistentBuilder, $name], $arguments));
    }
}
