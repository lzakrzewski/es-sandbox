<?php

namespace EsSandbox\Common\Infrastructure\CommandBus;

use Assert\Assertion;

final class EnforcingTypeServiceLocator
{
    /** @var callable */
    private $serviceLocator;

    /** @var string */
    private $type;

    public function __construct(callable $serviceLocator, $type)
    {
        $this->serviceLocator = $serviceLocator;
        $this->type           = $type;
    }

    public function __invoke($serviceId)
    {
        $service = call_user_func($this->serviceLocator, $serviceId);

        Assertion::isInstanceOf($service, $this->type);

        return $service;
    }
}
