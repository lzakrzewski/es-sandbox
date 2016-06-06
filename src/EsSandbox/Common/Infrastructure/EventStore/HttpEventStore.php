<?php

namespace EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Infrastructure\EventStore\Mapper\ShortNameToFQN;
use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventStore;
use HttpEventStore\EventStore as EventStoreClient;
use HttpEventStore\Exception\StreamDoesNotExist;
use Ramsey\Uuid\UuidInterface;

class HttpEventStore implements EventStore
{
    /** @var EventStoreClient */
    private $client;

    /** @var ShortNameToFQN */
    private $mapper;

    /**
     * @param EventStoreClient $client
     * @param ShortNameToFQN   $mapper
     */
    public function __construct(EventStoreClient $client, ShortNameToFQN $mapper)
    {
        $this->client = $client;
        $this->mapper = $mapper;
    }

    /** {@inheritdoc} */
    public function commit(array $events)
    {
        if (empty($events)) {
            return;
        }

        foreach ($this->segregateEventsByStreamId($events) as $streamId => $streamEvents) {
            $this->client->writeStream($streamId, $streamEvents);
        }
    }

    /** {@inheritdoc} */
    public function aggregateHistoryFor(UuidInterface $id)
    {
        try {
            $eventStoreEvents = $this->client->readStream($this->streamId($id));
        } catch (StreamDoesNotExist $e) {
            return AggregateHistory::of([]);
        }

        return AggregateHistory::of($this->mapToDomainEvents($eventStoreEvents));
    }

    private function streamId(UuidInterface $id)
    {
        return $id->toString();
    }

    private function segregateEventsByStreamId(array $events)
    {
        $streams = [];

        foreach ($events as $event) {
            $streamId = $event->id();

            if (isset($streams[(string) $streamId])) {
                continue;
            }

            $streams[(string) $streamId] = array_filter($events, function (Event $event) use ($streamId) {
                return $streamId->equals($event->id());
            });
        }

        return $streams;
    }

    private function mapToDomainEvents(array $eventStoreEvents)
    {
        $domainEvents = [];

        foreach ($eventStoreEvents as $eventStoreEvent) {
            $fqn            = $this->mapper->get($eventStoreEvent->type());
            $domainEvents[] = $fqn::fromData($eventStoreEvent->data());
        }

        return $domainEvents;
    }
}
