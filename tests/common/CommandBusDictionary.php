<?php

namespace tests\common;

use EsSandbox\Common\Application\CommandBus\Command;
use EsSandbox\Common\Application\CommandBus\CommandBus;
use EsSandbox\Common\Model\Event;
use tests\EsSandbox\Common\Infrastructure\CommandBus\CatchesExceptionMiddleware;
use tests\EsSandbox\Common\Infrastructure\CommandBus\CollectsEventsMiddleware;

trait CommandBusDictionary
{
    protected function handleCommand(Command $command)
    {
        $this->commandBus()->handle($command);
    }

    protected function expectsEvent($eventClass)
    {
        $expectedEvents = $this->lastRecordedEventsOf($eventClass);

        if (count($expectedEvents) > 0) {
            return;
        }

        \PHPUnit_Framework_Assert::fail(sprintf('Event "%s" has not been recorded', $eventClass));
    }

    protected function expectsNoEvents()
    {
        \PHPUnit_Framework_Assert::assertCount(0, $this->recordedEvents(), 'Expected no recorded events');
    }

    protected function expectsException($exceptionClass)
    {
        \PHPUnit_Framework_Assert::assertInstanceOf(
            $exceptionClass,
            $this->caughtException(),
            sprintf('Exception "%s" has not been thrown', $exceptionClass)
        );
    }

    /**
     * @return \Exception[]
     */
    protected function caughtException()
    {
        return $this->catchesExceptionMiddleware()->releaseException();
    }

    protected function enableCatchingExceptions()
    {
        $this->catchesExceptionMiddleware()->enableCatching();
    }

    protected function disableCatchingExceptions()
    {
        $this->catchesExceptionMiddleware()->disableCatching();
    }

    /**
     * @return Event[]
     */
    protected function recordedEvents()
    {
        return $this->collectsEventsMiddleware()->events();
    }

    /**
     * @param string $eventClass
     *
     * @return Event
     */
    protected function lastRecordedEventsOf($eventClass)
    {
        $expectedEvents = array_filter($this->recordedEvents(), function ($event) use ($eventClass) {
            return $event instanceof $eventClass;
        });

        $lastEvent = end($expectedEvents);

        return ($lastEvent) ?: null;
    }

    /** @return CommandBus */
    private function commandBus()
    {
        return $this->container()->get('client_panel.command_bus');
    }

    /** @return CollectsEventsMiddleware */
    private function collectsEventsMiddleware()
    {
        return $this->container()->get('client_panel.command_bus.collects_events');
    }

    /** @return CatchesExceptionMiddleware */
    private function catchesExceptionMiddleware()
    {
        return $this->container()->get('client_panel.command_bus.catches_exception');
    }
}
