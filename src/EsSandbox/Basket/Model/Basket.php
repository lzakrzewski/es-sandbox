<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventSourcedAggregate;
use EsSandbox\Common\Model\UnableToReconstitute;
use Ramsey\Uuid\UuidInterface;

final class Basket implements EventSourcedAggregate
{
    /** @var UuidInterface */
    private $basketId;

    /** @var Event[] */
    private $uncommittedEvents = [];

    private $products = [];

    private function __construct()
    {
    }

    /**
     * @param UuidInterface $basketId
     *
     * @return Basket
     */
    public static function pickUp(UuidInterface $basketId)
    {
        $self  = new self();
        $event = new BasketWasPickedUp($basketId);

        $self->applyBasketWasPickedUp($event);

        $self->recordThat($event);

        return $self;
    }

    /**
     * @param UuidInterface $productId
     * @param $name
     */
    public function addProduct(UuidInterface $productId, $name)
    {
        $event = new ProductWasAddedToBasket($this->id(), $productId, $name);

        $this->applyProductWasAddedToBasket($event);

        $this->recordThat($event);
    }

    /**
     * @param UuidInterface $productId
     */
    public function removeProduct(UuidInterface $productId)
    {
        if (!$this->hasProduct($productId)) {
            throw new ProductDoesNotExist(
                sprintf(
                    'Product with id %s does not exist within basket with id %s',
                    $productId,
                    $this->basketId
                )
            );
        }

        $event = new ProductWasRemovedFromBasket($this->id(), $productId);

        $this->applyProductWasRemovedFromBasket($event);

        $this->recordThat($event);
    }

    /**
     * @param AggregateHistory $history
     *
     * @return Basket
     */
    public static function reconstituteFrom(AggregateHistory $history)
    {
        $self = new self();

        $self->validateHistory($history);

        foreach ($history as $event) {
            $reflection = new \ReflectionClass($event);
            $method     = 'apply'.$reflection->getShortName();

            $self->$method($event);
        }

        return $self;
    }

    /** {@inheritdoc} */
    public function recordThat(Event $event)
    {
        $this->uncommittedEvents[] = $event;
    }

    /** {@inheritdoc} */
    public function id()
    {
        return $this->basketId;
    }

    /** {@inheritdoc} */
    public function uncommittedEvents()
    {
        $uncommittedEvents = $this->uncommittedEvents;

        $this->uncommittedEvents = [];

        return $uncommittedEvents;
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->products);
    }

    /** @SuppressWarnings(PHPMD.UnusedPrivateMethod) */
    private function applyBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->basketId = $event->id();
    }

    private function applyProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        $this->products[(string) $event->productId()] = $event->name();
    }

    private function applyProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        if ($this->hasProduct($productId = $event->productId())) {
            unset($this->products[(string) $productId]);
        }
    }

    private function hasProduct(UuidInterface $productId)
    {
        return isset($this->products[(string) $productId]);
    }

    /** @SuppressWarnings(PHPMD.UnusedPrivateMethod) */
    private function validateHistory(AggregateHistory $history)
    {
        if (0 === $history->count()) {
            throw new UnableToReconstitute('Unable to reconstitute Basket aggregate without any events.');
        }

        if (!$history[0] instanceof BasketWasPickedUp) {
            throw new UnableToReconstitute(
                sprintf('Unable to reconstitute Basket aggregate without initial "%s" event.', BasketWasPickedUp::class)
            );
        }
    }
}
