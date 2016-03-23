<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\AggregateRoot;
use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\RecordsEvents;

//Todo: handle cases when unable to reconstruct
//Todo: add trait to simplify aplying events
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

    /**
     * @param ProductId $productId
     * @param $name
     */
    public function addProduct(ProductId $productId, $name)
    {
        $this->products[(string) $productId] = $name;

        $this->recordThat(new ProductWasAddedToBasket($this->id(), $productId, $name));
    }

    /**
     * @param ProductId $productId
     */
    public function removeProduct(ProductId $productId)
    {
        if (!$this->hasProduct($productId)) {
            throw new \DomainException(
                sprintf(
                    'Product with id %s does not exist within basket with id %s',
                    $productId,
                    $this->basketId
                )
            );
        }

        unset($this->products[(string) $productId]);

        $this->recordThat(new ProductWasRemovedFromBasket($this->id(), $productId));
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

    /** @SuppressWarnings(PHPMD.UnusedPrivateMethod) */
    private function applyProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        $this->products[(string) $event->productId()] = $event->name();
    }

    /** @SuppressWarnings(PHPMD.UnusedPrivateMethod) */
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
