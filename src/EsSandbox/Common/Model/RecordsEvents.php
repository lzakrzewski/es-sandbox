<?php

namespace EsSandbox\Common\Model;

use EsSandbox\Common\Application\CommandBus\DomainEvents;

trait RecordsEvents
{
    public function recordThat(Event $event)
    {
        DomainEvents::instance()->record($event);
    }
}
