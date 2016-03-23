<?php

namespace EsSandbox\Common\Application\CommandBus;

use EsSandbox\Common\Model\Event;
use SimpleBus\Message\Recorder\ContainsRecordedMessages;

final class RecordedEvents implements ContainsRecordedMessages
{
    /** @var RecordedEvents */
    private static $instance;

    /** @var Event[] */
    private $events = [];

    private function __construct()
    {
    }

    /**
     * @return RecordedEvents
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @param Event $event
     */
    public function record(Event $event)
    {
        $this->events[] = $event;
    }

    /** {@inheritdoc} */
    public function recordedMessages()
    {
        return $this->events;
    }

    /** {@inheritdoc} */
    public function eraseMessages()
    {
        $this->events = [];
    }
}
