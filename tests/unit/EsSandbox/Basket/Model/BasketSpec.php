<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductDoesNotExist;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Model\AggregateHistory;
use PhpSpec\ObjectBehavior;

/**
 * @mixin Basket
 */
class BasketSpec extends ObjectBehavior
{
    public function it_can_be_picked_up()
    {
        $basketId = BasketId::generate();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->shouldBeAnInstanceOf(Basket::class);
    }

    public function it_has_id()
    {
        $basketId = BasketId::generate();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->id()->shouldBeLike($basketId);
    }

    public function it_can_add_product()
    {
        $basketId  = BasketId::generate();
        $productId = ProductId::generate();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->addProduct($productId, 'Teapot');

        $this->count()->shouldBe(1);
    }

    public function it_can_remove_product()
    {
        $basketId  = BasketId::generate();
        $productId = ProductId::generate();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->addProduct($productId, 'Teapot');
        $this->removeProduct($productId);

        $this->count()->shouldBe(0);
    }

    public function it_fails_when_product_to_remove_does_not_exists_within_basket()
    {
        $basketId  = BasketId::generate();
        $productId = ProductId::generate();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->shouldThrow(ProductDoesNotExist::class)->during('removeProduct', [$productId]);
    }

    public function it_has_count_of_products()
    {
        $basketId = BasketId::generate();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->count()->shouldBe(0);
    }

    public function it_can_be_picked_up_basing_on_history()
    {
        $basketId = BasketId::generate();

        $this->given([
            new BasketWasPickedUp($basketId),
        ]);

        $this->shouldBeLike(Basket::pickUp($basketId));
    }

    public function it_can_be_picked_up_with_added_product_basing_on_history()
    {
        $basketId  = BasketId::generate();
        $productId = ProductId::generate();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, $productId, 'Teapot'),
        ]);

        $basket = Basket::pickUp($basketId);
        $basket->addProduct($productId, 'Teapot');

        $this->shouldBeLike($basket);
    }

    public function it_can_be_picked_up_with_removed_product_basing_on_history()
    {
        $basketId  = BasketId::generate();
        $productId = ProductId::generate();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, $productId, 'Teapot'),
            new ProductWasRemovedFromBasket($basketId, $productId),
        ]);

        $this->shouldBeLike(Basket::pickUp($basketId));
    }

    private function given(array $events)
    {
        $this->beConstructedThrough(
            'reconstituteFrom',
            [
                AggregateHistory::of(
                    $events[0]->id(),
                    $events
                ),
            ]
        );
    }
}
