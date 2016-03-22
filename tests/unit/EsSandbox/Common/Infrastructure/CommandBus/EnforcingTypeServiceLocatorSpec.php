<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Infrastructure\CommandBus\EnforcingTypeServiceLocator;
use PhpSpec\ObjectBehavior;
use tests\unit\EsSandbox\Common\Infrastructure\CommandBus\Fixtures\ServiceType;

/**
 * @mixin EnforcingTypeServiceLocator
 */
class EnforcingTypeServiceLocatorSpec extends ObjectBehavior
{
    const TYPE = 'tests\unit\EsSandbox\Common\Infrastructure\CommandBus\Fixtures\ServiceType';

    private $services = [];

    public function let()
    {
        $serviceLocator = function ($serviceId) {
            return $this->services[$serviceId];
        };

        $this->beConstructedWith($serviceLocator, self::TYPE);
    }

    public function it_returns_a_known_service(ServiceType $knownService)
    {
        $this->services['known_service_id'] = $knownService->getWrappedObject();

        $service = $this->__invoke('known_service_id');

        $this->__invoke('known_service_id')->shouldBe($service);
    }

    public function it_fails_if_service_is_not_an_object_of_the_right_class()
    {
        $service = new \stdClass();

        $this->services['known_service_id'] = $service;

        $this->shouldThrow(\LogicException::class)->during('__invoke', ['known_service_id']);
    }
}
