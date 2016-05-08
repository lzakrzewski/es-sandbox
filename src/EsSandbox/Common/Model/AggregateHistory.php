<?php

namespace EsSandbox\Common\Model;

final class AggregateHistory extends \SplFixedArray
{
    /**
     * @param Event[] $events
     */
    public function __construct(array $events)
    {
        parent::__construct(count($events));

        $eventIdx = 0;

        foreach ($events as $event) {
            $this->guardType($event);
            parent::offsetSet($eventIdx++, $event);
        }
    }

    /**
     * @param array $events
     *
     * @return AggregateHistory
     */
    public static function of(array $events)
    {
        return new self($events);
    }

    /** {@inheritdoc} */
    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('AggregateHistory is immutable');
    }

    /** {@inheritdoc} */
    public function offsetUnset($offset)
    {
        throw new \RuntimeException('AggregateHistory is immutable');
    }

    private function guardType($event)
    {
        if (!$event instanceof Event) {
            throw new \InvalidArgumentException(sprintf('Expected type of %s, but %s given', Event::class, get_class($event)));
        }
    }
}
