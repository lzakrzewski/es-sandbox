<?php

namespace EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventStore;
use EsSandbox\Common\Model\Identifier;

/**
 * Todo: Remove this.
 *
 * @deprecated
 */
final class InMemoryEventStore implements EventStore
{
    /** @var self */
    private static $instance;

    /** @var array */
    private $events = [];

    private function __construct()
    {
    }

    /**
     * @return InMemoryEventStore
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** {@inheritdoc} */
    public function commit(Event $event)
    {
        $this->events[(string) $event->id()][] = $event;
    }

    /** {@inheritdoc} */
    public function aggregateHistoryFor(Identifier $id)
    {
        if (!$this->has($id)) {
            return AggregateHistory::of($id, []);
        }

        return AggregateHistory::of($id, $this->events[(string) $id]);
    }

    public function reset()
    {
        $this->events = [];
    }

    public function events()
    {
        $collectedEvents = [];

        foreach ($this->events as $events) {
            foreach ($events as $event) {
                $collectedEvents[] = $event;
            }
        }

        return $collectedEvents;
    }

    private function has($id)
    {
        return isset($this->events[(string) $id]);
    }
}
