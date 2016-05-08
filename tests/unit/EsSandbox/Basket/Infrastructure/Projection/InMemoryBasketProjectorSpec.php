<?php

namespace tests\unit\EsSandbox\Basket\Infrastructure\Projection;

use Assert\Assertion;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryBasketProjector;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @mixin InMemoryBasketProjector
 */
class InMemoryBasketProjectorSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(InMemoryStorage::instance());
    }

    public function it_can_apply_that_product_was_added_to_basket()
    {
        $basketId  = Uuid::fromString('4ea0aafc-79e1-4f55-9610-a9075a71fcef');
        $productId = Uuid::fromString('a8eb9e6c-d16e-442e-9d18-aa9caf939594');

        $this->applyProductWasAddedToBasket(new ProductWasAddedToBasket($basketId, $productId, 'Teapot'));

        Assertion::eq(
            InMemoryStorage::instance()->read('4ea0aafc-79e1-4f55-9610-a9075a71fcef'),
            [[
                'productId' => 'a8eb9e6c-d16e-442e-9d18-aa9caf939594',
                'name'      => 'Teapot',
            ]]
        );
    }

    public function it_can_apply_that_product_was_removed_from_basket()
    {
        $basketId  = Uuid::fromString('4ea0aafc-79e1-4f55-9610-a9075a71fcef');
        $productId = Uuid::fromString('a8eb9e6c-d16e-442e-9d18-aa9caf939594');

        $this->applyProductWasAddedToBasket(new ProductWasAddedToBasket($basketId, $productId, 'Teapot'));
        $this->applyProductWasRemovedFromBasket(new ProductWasRemovedFromBasket($basketId, $productId));

        Assertion::eq(
            InMemoryStorage::instance()->read('4ea0aafc-79e1-4f55-9610-a9075a71fcef'),
            []
        );
    }

    public function it_can_apply_that_product_was_removed_from_basket_when_no_products()
    {
        $basketId  = Uuid::fromString('4ea0aafc-79e1-4f55-9610-a9075a71fcef');
        $productId = Uuid::fromString('a8eb9e6c-d16e-442e-9d18-aa9caf939594');

        $this->applyProductWasRemovedFromBasket(new ProductWasRemovedFromBasket($basketId, $productId));

        Assertion::eq(
            InMemoryStorage::instance()->read('4ea0aafc-79e1-4f55-9610-a9075a71fcef'),
            []
        );
    }

    public function letGo()
    {
        InMemoryStorage::instance()->clear();
    }
}
