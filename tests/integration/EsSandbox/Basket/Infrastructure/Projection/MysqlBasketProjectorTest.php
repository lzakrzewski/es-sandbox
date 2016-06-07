<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection;

use Doctrine\ORM\EntityManager;
use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SimpleBus\Message\Bus\MessageBus;
use tests\integration\DatabaseTestCase;

class MysqlBasketProjectorTest extends DatabaseTestCase
{
    /** @var MessageBus */
    private $eventBus;

    /** @test */
    public function it_applies_that_basket_was_picked_up()
    {
        $basketId = Uuid::uuid4();
        $this->given([new BasketWasPickedUp($basketId)]);

        $this->assertThatProjectionWasCreated($basketId);
    }

    /** @test */
    public function it_applies_that_product_was_added_to_basket()
    {
        $basketId = Uuid::uuid4();
        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Iron'),
        ]);

        $this->assertThatBasketProjectionHasProducts(1, $basketId);
    }

    /** @test */
    public function it_does_not_applies_that_product_was_added_to_basket_when_basket_was_not_picked_up()
    {
        $basketId = Uuid::uuid4();
        $this->given([
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Iron'),
        ]);

        $this->assertThatProjectionWasNotCreated($basketId);
    }

    /** @test */
    public function it_applies_that_product_was_removed_from_basket()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, $productId, 'Iron'),
            new ProductWasRemovedFromBasket($basketId, $productId),
        ]);

        $this->assertThatBasketProjectionHasProducts(0, $basketId);
    }

    /** @test */
    public function it_does_not_applies_that_product_was_removed_from_basket_when_basket_was_not_picked_up()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();
        $this->given([
            new ProductWasAddedToBasket($basketId, $productId, 'Iron'),
            new ProductWasRemovedFromBasket($basketId, $productId),
        ]);

        $this->assertThatProjectionWasNotCreated($basketId);
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->eventBus = $this->container()->get('event_bus');
    }

    /** {@inheritdoc} */
    public function tearDown()
    {
        $this->eventBus = null;

        parent::tearDown();
    }

    private function assertThatProjectionWasCreated(UuidInterface $basketId)
    {
        $this->assertNotNull($this->basketView($basketId));
    }

    private function assertThatProjectionWasNotCreated(UuidInterface $basketId)
    {
        $this->assertNull($this->basketView($basketId));
    }

    private function assertThatBasketProjectionHasProducts($expectedProductsCount, UuidInterface $basketId)
    {
        $this->assertCount($expectedProductsCount, $this->basketView($basketId)->products);
    }

    private function given(array $events)
    {
        foreach ($events as $event) {
            $this->eventBus->handle($event);
        }
    }

    private function basketView(UuidInterface $basketId)
    {
        return $this->entityManager
            ->getRepository(BasketView::class)
            ->findOneBy(['basketId' => $basketId->toString()]);
    }
}
