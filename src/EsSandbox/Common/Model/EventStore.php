<?php

namespace EsSandbox\Common\Model;

//Todo: Commit multiple events
use Ramsey\Uuid\UuidInterface;

interface EventStore
{
    /**
     * @param Event $event
     */
    public function commit(Event $event);

    /**
     * @param UuidInterface $id
     *
     * @return AggregateHistory
     */
    public function aggregateHistoryFor(UuidInterface $id);
}
