<?php

namespace EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Application\CommandBus\RecordedEvents;
use EsSandbox\Common\Model\EventStore;
use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class CommitRecordedEventsMiddleware implements MessageBusMiddleware
{
    /** @var EventStore */
    private $eventStore;

    /** @var RecordedEvents */
    private $recordedEvents;

    /**
     * @param EventStore     $eventStore
     * @param RecordedEvents $recordedEvents
     */
    public function __construct(EventStore $eventStore, RecordedEvents $recordedEvents)
    {
        $this->eventStore     = $eventStore;
        $this->recordedEvents = $recordedEvents;
    }

    /** {@inheritdoc} */
    public function handle($message, callable $next)
    {
        foreach ($this->recordedEvents->recordedMessages() as $event) {
            $this->eventStore->commit($event);
        }

        $this->recordedEvents->eraseMessages();
    }
}
