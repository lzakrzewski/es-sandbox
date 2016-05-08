<?php

namespace EsSandbox\Common\Model;

use Ramsey\Uuid\UuidInterface;

interface EventStore
{
    /**
     * @param Event[] $events
     */
    public function commit(array $events);

    /**
     * @param UuidInterface $id
     *
     * @return AggregateHistory
     */
    public function aggregateHistoryFor(UuidInterface $id);
}
