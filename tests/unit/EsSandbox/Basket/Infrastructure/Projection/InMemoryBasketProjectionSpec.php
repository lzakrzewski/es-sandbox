<?php

namespace tests\unit\EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\ProductView;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryBasketProjection;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryBasketProjector;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use PhpSpec\ObjectBehavior;

/**
 * @mixin InMemoryBasketProjection
 */
class InMemoryBasketProjectionSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedWith(InMemoryStorage::instance());
    }

    public function it_can_get_products_in_basket()
    {
        $basketId   = BasketId::generate();
        $productId1 = ProductId::generate();
        $productId2 = ProductId::generate();
        $productId3 = ProductId::generate();

        $this->projectThatProductWasAddedToBasket($basketId, $productId1, 'Teapot');
        $this->projectThatProductWasAddedToBasket($basketId, $productId2, 'Iron');
        $this->projectThatProductWasAddedToBasket($basketId, $productId3, 'Phone');
        $this->projectThatProductWasRemovedFromBasket($basketId, $productId3);

        $this->get($basketId->raw())
            ->shouldBeLike(
                [
                    new ProductView((string) $productId1, 'Teapot'),
                    new ProductView((string) $productId2, 'Iron'),
                ]
            );
    }

    public function it_can_get_empty_basket_when_no_products()
    {
        $basketId = BasketId::generate();

        $this->get($basketId->raw())->shouldBeLike([]);
    }

    public function letGo()
    {
        InMemoryStorage::instance()->clear();
    }

    private function projectThatProductWasAddedToBasket(BasketId $basketId, ProductId $productId, $name)
    {
        (new InMemoryBasketProjector(InMemoryStorage::instance()))
            ->applyProductWasAddedToBasket(new ProductWasAddedToBasket($basketId, $productId, $name));
    }

    private function projectThatProductWasRemovedFromBasket(BasketId $basketId, ProductId $productId)
    {
        (new InMemoryBasketProjector(InMemoryStorage::instance()))
            ->applyProductWasRemovedFromBasket(new ProductWasRemovedFromBasket($basketId, $productId));
    }
}
