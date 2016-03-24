<?php

namespace EsSandbox\Common\Model;

trait ApplyEvents
{
    /**
     * @param Event $event
     */
    protected function apply(Event $event)
    {
        $reflection = new \ReflectionClass($event);
        $method     = 'apply'.$reflection->getShortName();

        $this->$method($event);
    }
}
