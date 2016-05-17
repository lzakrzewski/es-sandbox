<?php

namespace EsSandbox\Common\Model;

use Ramsey\Uuid\UuidInterface;

interface EventSourcedAggregate
{
    /**
     * @return UuidInterface
     */
    public function id();

    /**
     * @param Event $event
     */
    public function recordThat(Event $event);

    /**
     * @param AggregateHistory $history
     *
     * @return EventSourcedAggregate
     */
    public static function reconstituteFrom(AggregateHistory $history);

    /**
     * @return Event[]
     */
    public function uncommittedEvents();
}
