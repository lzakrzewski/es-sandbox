<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketProjection;
use EsSandbox\Basket\Application\Projection\ProductView;
use Ramsey\Uuid\UuidInterface;

final class InMemoryBasketProjection implements BasketProjection
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

    /** {@inheritdoc} */
    public function get(UuidInterface $basketId)
    {
        $result = [];

        if (!$this->storage->has((string) $basketId)) {
            return $result;
        }

        foreach ($this->storage->read((string) $basketId) as $product) {
            if (!empty($product) && isset($product['productId']) && isset($product['name'])) {
                $result[] = new ProductView($product['productId'], $product['name']);
            }
        }

        return $result;
    }
}
