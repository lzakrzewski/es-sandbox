<?php

namespace EsSandbox\Common\Model;

/** @SuppressWarnings(PHPMD.UnusedPrivateField) */
final class AggregateHistory extends \SplFixedArray
{
    /** @var Identifier */
    private $id;

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
     * @param Identifier $id
     * @param array      $events
     *
     * @return AggregateHistory
     */
    public static function of(Identifier $id, array $events)
    {
        $self     = new self($events);
        $self->id = $id;

        return $self;
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
