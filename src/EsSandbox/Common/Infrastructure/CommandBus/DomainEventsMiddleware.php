<?php

namespace EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Application\CommandBus\DomainEvents;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class DomainEventsMiddleware implements MessageBusMiddleware
{
    /** @var DomainEvents */
    private $domainEvents;

    public function __construct(DomainEvents $domainEvents)
    {
        $this->domainEvents = $domainEvents;
    }

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        $this->domainEvents->enableRecording();

        try {
            $next($message);
        } catch (\Exception $e) {
            $this->domainEvents->disableRecording();

            throw $e;
        }

        $this->domainEvents->disableRecording();
    }
}
