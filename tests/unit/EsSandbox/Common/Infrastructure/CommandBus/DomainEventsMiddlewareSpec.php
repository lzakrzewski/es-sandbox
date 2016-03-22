<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\CommandBus;

use Assert\Assertion;
use EsSandbox\Common\Application\CommandBus\DomainEvents;
use EsSandbox\Common\Infrastructure\CommandBus\DomainEventsMiddleware;
use EsSandbox\Common\Model\Event;
use PhpSpec\Exception\Example\FailureException;
use PhpSpec\ObjectBehavior;
use tests\unit\EsSandbox\Common\Infrastructure\CommandBus\Fixtures\FakeEvent;

/**
 * @mixin DomainEventsMiddleware
 */
class DomainEventsMiddlewareSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(DomainEvents::instance());
    }

    public function it_enables_recording_before_next()
    {
        $event = new FakeEvent();

        $this->handle($this->message(), $this->callableThatRecords($event));

        $this->shouldHaveAnEventRecorded($event);
    }

    public function it_disables_recording_after_next()
    {
        $this->handle($this->message(), $this->dummyCallable());

        $this->shouldHaveNoEventsRecorded();
    }

    public function it_disables_recording_when_next_thrown_exception()
    {
        try {
            $this->handle($this->message(), $this->callableThatThrowsException());

            throw new \Exception('An exception should have been thrown');
        } catch (\Exception $e) {
        }

        $this->shouldHaveNoEventsRecorded();
    }

    public function letGo()
    {
        DomainEvents::instance()->eraseMessages();
    }

    public function getMatchers()
    {
        return [
            'haveAnEventRecorded' => function (DomainEventsMiddleware $middleware, Event $event) {
                try {
                    Assertion::inArray($event, DomainEvents::instance()->recordedMessages());
                } catch (\Exception $e) {
                    throw new FailureException($e->getMessage());
                }

                return true;
            },
            'haveNoEventsRecorded' => function (DomainEventsMiddleware $middleware) {
                try {
                    Assertion::same([], DomainEvents::instance()->recordedMessages());
                } catch (\Exception $e) {
                    throw new FailureException($e->getMessage());
                }

                return true;
            },
        ];
    }

    private function message()
    {
        return new \stdClass();
    }

    private function callableThatRecords(Event $event)
    {
        $domainEvents = DomainEvents::instance();

        return function () use ($domainEvents, $event) {
            $domainEvents->record($event);
        };
    }

    private function dummyCallable()
    {
        return function () {

        };
    }

    private function callableThatThrowsException()
    {
        return function () {
            throw new \Exception();
        };
    }
}
