<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketProjection;
use GuzzleHttp\Client;
use Ramsey\Uuid\UuidInterface;

class GuzzleEventStoreBasketProjection implements BasketProjection
{
    /** @var Client */
    private $client;

    /** @var string */
    private $uri;

    /**
     * @param Client $client
     * @param string $host
     * @param string $port
     */
    public function __construct(Client $client, $host, $port)
    {
        $this->client = $client;
        $this->uri    = sprintf('%s:%s', $host, $port);
    }

    /** {@inheritdoc} */
    public function get(UuidInterface $basketId)
    {
        $this->createProjection($basketId);

        $response = $this->client
            ->request(
                'GET',
                sprintf('%s/projection/%s/result', $this->uri, $this->projectionName($basketId)),
                [
                    'headers' => [
                        'Accept' => ['application/json'],
                    ],
                ]
            );

        return (array) json_decode($response->getBody()->getContents(), true);
    }

    private function createProjection(UuidInterface $basketId)
    {
        $this->client->request(
            'POST',
            sprintf('%s/projections/onetime?name=%s&enabled=yes', $this->uri, $this->projectionName($basketId)),
            [
                'headers' => [
                    'Content-Type' => ['application/json'],
                ],
                'body' => $this->projectionFunc($basketId),
                'auth' => ['admin', 'changeit'],
            ]
        );
    }

    private function projectionFunc(UuidInterface $basketId)
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
}
