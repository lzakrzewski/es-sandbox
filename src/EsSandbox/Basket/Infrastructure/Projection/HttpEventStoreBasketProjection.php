<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketProjection;
use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Application\Projection\ProductView;
use HttpEventStore\Projection as EventStoreProjectionClient;
use Ramsey\Uuid\UuidInterface;

class HttpEventStoreBasketProjection implements BasketProjection
{
    /** @var EventStoreProjectionClient */
    private $client;

    /**
     * @param EventStoreProjectionClient $client
     */
    public function __construct(EventStoreProjectionClient $client)
    {
        $this->client = $client;
    }

    /** {@inheritdoc} */
    public function get(UuidInterface $basketId)
    {
        try {
            $this->createProjection($basketId);
        } catch (\Exception $e) {
        }

        $result = $this
            ->client
            ->readProjection($this->projectionName($basketId));

        if (empty($result)) {
            return;
        }

        return $this->mapToBasketView($basketId, $result);
    }

    private function createProjection(UuidInterface $basketId)
    {
        $this
            ->client
            ->createProjection(
                $this->projectionName($basketId),
                $this->projectionQuery($basketId)
            );
    }

    private function projectionQuery(UuidInterface $basketId)
    {
        $name = $this->streamName($basketId);

        return <<<STR
fromStream('$name')
    .when({
        \$init: function(state, event) {
            return {
                basket: {
                    products: []
                }
            }
        },
        \$any: function(state, event) {
            if (event.eventType == "ProductWasAddedToBasket") {
                return addProduct(event.body, state);
            }

            if (event.eventType == "ProductWasRemovedFromBasket") {
                return removeProduct(event.body.productId, state);
            }

            return state;
        }
    });

addProduct = function(product, state) {
    state.basket.products.push({
        name: product.name,
        productId: product.productId
    });

    return state;
}

removeProduct = function(productIdToRemove, state) {
    state.basket.products = function() {
        var idx,
            products = [];

        for (idx in state.basket.products) {
            if (state.basket.products[idx].productId !== productIdToRemove) {
                products.push(state.basket.products[idx]);
            }
        }

        return products;
    }();

    return state;
}
STR;
    }

    private function projectionName(UuidInterface $id)
    {
        return sprintf('%s-%s', 'basket-projection', $id);
    }

    private function streamName(UuidInterface $basketId)
    {
        return $basketId->toString();
    }

    private function mapToBasketView(UuidInterface $basketId, array $result)
    {
        if (isset($result['basket']) && isset($result['basket']['products'])) {
            $views = [];

            foreach ($result['basket']['products'] as $productData) {
                $views[] = new ProductView($productData['productId'], $productData['name']);
            }

            return new BasketView($basketId->toString(), $views);
        }

        return new BasketView($basketId->toString(), []);
    }
}
