<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\ProductView;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryBasketProjection;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Infrastructure\EventStore\InMemoryEventStore;
use tests\integration\IntegrationTestCase;

class InMemoryBasketProjectionTest extends IntegrationTestCase
{
    /** @var InMemoryBasketProjection */
    private $projection;

    /** @test */
    public function it_can_get_products_in_basket()
    {
        $basketId   = BasketId::generate();
        $productId1 = ProductId::generate();
        $productId2 = ProductId::generate();
        $productId3 = ProductId::generate();

        $this->given([
            new ProductWasAddedToBasket($basketId, $productId1, 'Teapot'),
            new ProductWasAddedToBasket($basketId, $productId2, 'Iron'),
            new ProductWasAddedToBasket($basketId, $productId3, 'Phone'),
            new ProductWasRemovedFromBasket($basketId, $productId3),
        ]);

        $products = $this->projection->get($basketId->raw());

        $this->assertEquals([
            new ProductView((string) $productId1, 'Teapot'),
            new ProductView((string) $productId2, 'Iron'),
        ], $products);
    }

    public function it_can_get_empty_basket_when_no_products()
    {
        $basketId = BasketId::generate();

        $this->assertEmpty($this->projection->get($basketId->raw()));
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->projection = $this->container()->get('es_sandbox.projection.basket');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        parent::tearDown();

        $this->projection = null;

        InMemoryStorage::instance()->clear();
        InMemoryEventStore::instance()->reset();
    }

    private function given(array $events)
    {
        foreach ($events as $event) {
            $this->container()->get('event_bus')->handle($event);
        }
    }
}
