<?php

namespace tests\EsSandbox\Common\Infrastructure\CommandBus;

use Assert\Assertion;
use EsSandbox\Common\Model\Event;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class CollectsEventsMiddleware implements MessageBusMiddleware
{
    /** @var Event[] */
    private $events = [];

    /**
     * {@inheritdoc}
     */
    public function handle($event, callable $next)
    {
        $this->collect($event);

        $next($event);
    }

    /**
     * @return Event[]
     */
    public function events()
    {
        return $this->events;
    }

    private function collect($event)
    {
        Assertion::isInstanceOf($event, Event::class);

        $this->events[] = $event;
    }
}
