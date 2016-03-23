<?php

namespace EsSandbox\Common\Model;

interface EventStore
{
    /**
     * @param Event $event
     */
    public function commit(Event $event);

    /**
     * @param Identifier $id
     *
     * @return AggregateHistory
     */
    public function aggregateHistoryFor(Identifier $id);
}
