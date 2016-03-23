<?php

namespace EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Application\CommandBus\DomainEvents;
use EsSandbox\Common\Model\EventStore;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

//Todo: CommitRecorded events
class CommitEventsMiddleware implements MessageBusMiddleware
{
    /** @var EventStore */
    private $store;

    /**
     * @param EventStore $store
     */
    public function __construct(EventStore $store)
    {
        $this->store = $store;
    }

    /** {@inheritdoc} */
    public function handle($message, callable $next)
    {
        foreach (DomainEvents::instance()->recordedMessages() as $event) {
            $this->store->commit($event);
        }

        DomainEvents::instance()->eraseMessages();
    }
}
