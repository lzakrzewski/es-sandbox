<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\AggregateRoot;
use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\RecordsEvents;

//Todo: handle cases when unable to reconstruct
final class Basket implements AggregateRoot
{
    use RecordsEvents;

    /** @var BasketId */
    private $basketId;

    private $products = [];

    private function __construct()
    {
    }

    /**
     * @param BasketId $basketId
     *
     * @return Basket
     */
    public static function pickUp(BasketId $basketId)
    {
        $self           = new self();
        $self->basketId = $basketId;

        $self->recordThat(new BasketWasPickedUp($basketId));

        return $self;
    }

    /**
     * @return BasketId
     */
    public function id()
    {
        return $this->basketId;
    }

    public static function reconstituteFrom(AggregateHistory $history)
    {
        $self = new self();

        foreach ($history as $event) {
            $self->apply($event);
        }

        return $self;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->products);
    }

    /** @SuppressWarnings(PHPMD.UnusedPrivateMethod) */
    private function apply(Event $event)
    {
        $reflection = new \ReflectionClass($event);
        $method     = 'apply'.$reflection->getShortName();

        $this->$method($event);
    }

    /** @SuppressWarnings(PHPMD.UnusedPrivateMethod) */
    private function applyBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->basketId = $event->id();
    }
}
