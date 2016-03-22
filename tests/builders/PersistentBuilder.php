<?php

namespace tests\builders;

use Symfony\Component\DependencyInjection\ContainerInterface;

interface PersistentBuilder
{
    public function persist(ContainerInterface $container, $builtObject);
}
