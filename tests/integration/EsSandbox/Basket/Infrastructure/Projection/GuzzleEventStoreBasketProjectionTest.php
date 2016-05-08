<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Infrastructure\Projection\GuzzleEventStoreBasketProjection;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use tests\integration\IntegrationTestCase;

class GuzzleEventStoreBasketProjectionTest extends IntegrationTestCase
{
    /** @var GuzzleEventStoreBasketProjection */
    private $projection;

    /** @test */
    public function it_can_get_products_in_basket()
    {
        $basketId   = BasketId::generate();
        $productId1 = ProductId::fromString('ddf37fb1-6869-499d-9b9b-c4f10ad32782');
        $productId2 = ProductId::fromString('4d72292b-67ca-477c-83ea-ec8e0406b251');
        $productId3 = ProductId::fromString('ec5e512e-3513-43ad-925b-f9496bf816f9');

        $this->given([
            new ProductWasAddedToBasket($basketId, $productId1, 'Teapot'),
            new ProductWasAddedToBasket($basketId, $productId2, 'Iron'),
            new ProductWasAddedToBasket($basketId, $productId3, 'Phone'),
            new ProductWasRemovedFromBasket($basketId, $productId3),
        ]);

        $basket = $this->projection->get($basketId->raw());

        $this->assertEquals([
            'basket' => [
                'products' => [
                    [
                        'name'      => 'Teapot',
                        'productId' => 'ddf37fb1-6869-499d-9b9b-c4f10ad32782',
                    ],
                    [
                        'name'      => 'Iron',
                        'productId' => '4d72292b-67ca-477c-83ea-ec8e0406b251',
                    ],
                ],
            ],
        ], $basket);
    }

    /** @test */
    public function it_can_get_empty_basket_when_no_products()
    {
        $basketId = BasketId::generate();

        $this->assertEmpty($this->projection->get($basketId->raw()));
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->projection = $this->container()->get('es_sandbox.projection.basket.guzzle_eventstore');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        parent::tearDown();

        $this->projection = null;
    }

    private function given(array $events)
    {
        foreach ($events as $event) {
            $this->container()->get('es_sandbox.event_store.guzzle_eventstore')->commit($event);
        }
    }
}
