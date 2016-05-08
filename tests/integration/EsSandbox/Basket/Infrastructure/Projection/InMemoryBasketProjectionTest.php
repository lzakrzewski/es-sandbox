<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\ProductView;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryBasketProjection;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Infrastructure\EventStore\InMemoryEventStore;
use Ramsey\Uuid\Uuid;
use tests\integration\IntegrationTestCase;

/**
 * @deprecated
 */
class InMemoryBasketProjectionTest extends IntegrationTestCase
{
    /** @var InMemoryBasketProjection */
    private $projection;

    /** @test */
    public function it_can_get_products_in_basket()
    {
        $basketId   = Uuid::uuid4();
        $productId1 = Uuid::uuid4();
        $productId2 = Uuid::uuid4();
        $productId3 = Uuid::uuid4();

        $this->given([
            new ProductWasAddedToBasket($basketId, $productId1, 'Teapot'),
            new ProductWasAddedToBasket($basketId, $productId2, 'Iron'),
            new ProductWasAddedToBasket($basketId, $productId3, 'Phone'),
            new ProductWasRemovedFromBasket($basketId, $productId3),
        ]);

        $products = $this->projection->get($basketId);

        $this->assertEquals([
            new ProductView((string) $productId1, 'Teapot'),
            new ProductView((string) $productId2, 'Iron'),
        ], $products);
    }

    public function it_can_get_empty_basket_when_no_products()
    {
        $basketId = Uuid::uuid4();

        $this->assertEmpty($this->projection->get($basketId));
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
