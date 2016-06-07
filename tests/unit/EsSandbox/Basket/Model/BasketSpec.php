<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductDoesNotExist;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\UnableToReconstitute;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @mixin Basket
 */
class BasketSpec extends ObjectBehavior
{
    public function it_can_be_picked_up()
    {
        $basketId = Uuid::uuid4();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->shouldBeAnInstanceOf(Basket::class);
        $this->uncommittedEvents()
            ->shouldBeLike(
                [new BasketWasPickedUp($basketId)]
            );
    }

    public function it_has_id()
    {
        $basketId = Uuid::uuid4();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->id()->shouldBeLike($basketId);
    }

    public function it_can_add_product()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->addProduct($productId, 'Teapot');

        $this->count()->shouldBe(1);
        $this->uncommittedEvents()->shouldBeLike(
            [
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, $productId, 'Teapot'),
            ]
        );
    }

    public function it_can_remove_product()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->addProduct($productId, 'Teapot');
        $this->removeProduct($productId);

        $this->count()->shouldBe(0);
        $this->uncommittedEvents()->shouldBeLike(
            [
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, $productId, 'Teapot'),
                new ProductWasRemovedFromBasket($basketId, $productId),
            ]
        );
    }

    public function it_fails_when_product_to_remove_does_not_exists_within_basket()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->shouldThrow(ProductDoesNotExist::class)->during('removeProduct', [$productId]);
    }

    public function it_has_count_of_products()
    {
        $basketId = Uuid::uuid4();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->count()->shouldBe(0);
    }

    public function it_can_be_picked_up_basing_on_history()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
        ]);

        $this->shouldBeAnInstanceOf(Basket::class);
        $this->id()->shouldBeLike($basketId);
        $this->uncommittedEvents()->shouldBeLike([]);
    }

    public function it_can_be_picked_up_with_added_product_basing_on_history()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, $productId, 'Teapot'),
        ]);

        $this->shouldBeAnInstanceOf(Basket::class);
        $this->id()->shouldBeLike($basketId);
        $this->count()->shouldBe(1);
        $this->uncommittedEvents()->shouldBeLike([]);
    }

    public function it_can_be_picked_up_with_removed_product_basing_on_history()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, $productId, 'Teapot'),
            new ProductWasRemovedFromBasket($basketId, $productId),
        ]);

        $this->shouldBeAnInstanceOf(Basket::class);
        $this->id()->shouldBeLike($basketId);
        $this->count()->shouldBe(0);
        $this->uncommittedEvents()->shouldBeLike([]);
    }

    public function it_can_not_be_reconstitute_from_empty_history()
    {
        $this->given([]);

        $this->shouldThrow(UnableToReconstitute::class)->duringInstantiation();
    }

    public function it_can_not_be_reconstitute_from_history_without_event_that_basket_was_picked_up()
    {
        $basketId  = Uuid::uuid4();
        $productId = Uuid::uuid4();

        $this->given([
            new ProductWasAddedToBasket($basketId, $productId, 'Teapot'),
            new ProductWasRemovedFromBasket($basketId, $productId),
        ]);

        $this->shouldThrow(UnableToReconstitute::class)->duringInstantiation();
    }

    private function given(array $events)
    {
        $this->beConstructedThrough(
            'reconstituteFrom',
            [
                AggregateHistory::of($events),
            ]
        );
    }
}
