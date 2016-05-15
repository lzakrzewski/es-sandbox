<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Application\Projection\ProductView;
use EsSandbox\Basket\Infrastructure\Projection\HttpEventStoreBasketProjection;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Model\EventStore;
use Ramsey\Uuid\Uuid;
use tests\integration\EsSandbox\Basket\Infrastructure\Projection\Dictionary\BasketProjectionDictionary;
use tests\integration\IntegrationTestCase;

class HttpEventStoreBasketProjectionTest extends IntegrationTestCase
{
    use BasketProjectionDictionary;

    /** @var EventStore */
    private $eventStore;

    /** @var HttpEventStoreBasketProjection */
    private $projection;

    /** @test */
    public function it_gets_basket_view()
    {
        $basketId   = Uuid::uuid4();
        $productId1 = Uuid::fromString('ddf37fb1-6869-499d-9b9b-c4f10ad32782');
        $productId2 = Uuid::fromString('4d72292b-67ca-477c-83ea-ec8e0406b251');
        $productId3 = Uuid::fromString('ec5e512e-3513-43ad-925b-f9496bf816f9');

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, $productId1, 'Teapot'),
            new ProductWasAddedToBasket($basketId, $productId2, 'Iron'),
            new ProductWasAddedToBasket($basketId, $productId3, 'Phone'),
            new ProductWasRemovedFromBasket($basketId, $productId3),
        ]);

        $basketView = $this->projection->get($basketId);

        $this->assertThatBasketViewEquals(
            new BasketView(
                $basketId->toString(),
                [
                    new ProductView($productId1->toString(), 'Teapot'),
                    new ProductView($productId2->toString(), 'Iron'),
                ]
            ),
            $basketView
        );
    }

    /** @test */
    public function it_gets_null_when_no_events()
    {
        $basketId = Uuid::uuid4();

        $this->assertNull($this->projection->get($basketId));
    }

    /** @test */
    public function it_gets_empty_basket_view_when_no_products()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
        ]);

        $basketView = $this->projection->get($basketId);

        $this->assertThatBasketViewEquals(
            new BasketView($basketId->toString(), []),
            $basketView
        );
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->eventStore = $this->container()->get('es_sandbox.event_store');
        $this->projection = $this->container()->get('es_sandbox.projection.basket.event_store');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        parent::tearDown();

        $this->eventStore = null;
        $this->projection = null;
    }

    private function given(array $events)
    {
        $this->eventStore->commit($events);
    }
}
