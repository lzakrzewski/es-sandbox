<?php

namespace EsSandbox\Common\Model;

interface AggregateRoot
{
    /**
     * @return Identifier
     */
    public function id();

    /**
     * @param Event $event
     */
    public function recordThat(Event $event);
}
