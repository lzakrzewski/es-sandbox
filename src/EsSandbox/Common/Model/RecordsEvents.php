<?php

namespace EsSandbox\Common\Model;

use EsSandbox\Common\Application\CommandBus\RecordedEvents;

trait RecordsEvents
{
    /**
     * @param Event $event
     */
    public function recordThat(Event $event)
    {
        RecordedEvents::instance()->record($event);
    }
}
