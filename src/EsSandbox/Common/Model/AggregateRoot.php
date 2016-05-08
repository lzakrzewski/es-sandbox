<?php

namespace EsSandbox\Common\Model;

use Ramsey\Uuid\UuidInterface;

interface AggregateRoot
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
     * @return AggregateRoot
     */
    public static function reconstituteFrom(AggregateHistory $history);

    /**
     * @return Event[]
     */
    public function uncommittedEvents();
}
