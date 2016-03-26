<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\AggregateRoot;
use EsSandbox\Common\Model\ApplyEvents;
use EsSandbox\Common\Model\RecordsEvents;

//Todo: handle cases when unable to reconstruct
final class Basket implements AggregateRoot
{
    use RecordsEvents;
    use ApplyEvents;

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
        $self  = new self();
        $event = new BasketWasPickedUp($basketId);

        $self->applyBasketWasPickedUp($event);

        $self->recordThat($event);

        return $self;
    }

    /**
     * @return BasketId
     */
    public function id()
    {
        return $this->basketId;
    }

    /**
     * @param ProductId $productId
     * @param $name
     */
    public function addProduct(ProductId $productId, $name)
    {
        $event = new ProductWasAddedToBasket($this->id(), $productId, $name);

        $this->applyProductWasAddedToBasket($event);

        $this->recordThat($event);
    }

    /**
     * @param ProductId $productId
     */
    public function removeProduct(ProductId $productId)
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

    private function hasProduct(ProductId $productId)
    {
        return isset($this->products[(string) $productId]);
    }
}
