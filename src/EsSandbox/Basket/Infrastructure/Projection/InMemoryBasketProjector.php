<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketProjector;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;

class InMemoryBasketProjector implements BasketProjector
{
    /** @var InMemoryStorage */
    private $storage;

    /**
     * @param InMemoryStorage $storage
     */
    public function __construct(InMemoryStorage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param ProductWasAddedToBasket $event
     */
    public function applyProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        $basketId = (string) $event->id();

        try {
            $products = $this->storage->read($basketId);
        } catch (\RuntimeException $e) {
            $products = [];
        }

        $products[] = ['productId' => (string) $event->productId(), 'name' => $event->name()];

        $this->storage->put((string) $event->id(), $products);
    }

    /**
     * @param ProductWasRemovedFromBasket $event
     */
    public function applyProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        $basketId = (string) $event->id();

        try {
            $products = $this->storage->read($basketId);
        } catch (\RuntimeException $e) {
            $products = [];
        }

        foreach ($products as $key => $product) {
            if (isset($product['productId']) && $product['productId'] == (string) $event->productId()) {
                unset($products[$key]);
            }
        }

        $this->storage->put((string) $event->id(), $products);
    }
}
