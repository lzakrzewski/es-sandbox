<?php

namespace tests\unit\EsSandbox\Basket\Application\Projection;

use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Application\Projection\ProductView;
use PhpSpec\ObjectBehavior;

/**
 * @mixin BasketView
 */
class BasketViewSpec extends ObjectBehavior
{
    public function it_can_be_created()
    {
        $this->beConstructedWith(
            'basket-id',
            [
                new ProductView('product-id', 'Teapot'),
            ]
        );

        $this->shouldBeAnInstanceOf(BasketView::class);
    }

    public function it_has_basket_id()
    {
        $this->beConstructedWith(
            'basket-id',
            [
                new ProductView('product-id', 'Teapot'),
            ]
        );

        $this->basketId->shouldBe('basket-id');
    }

    public function it_has_products()
    {
        $this->beConstructedWith(
            'basket-id',
            [
                new ProductView('product-id1', 'Teapot'),
                new ProductView('product-id2', 'Iron'),
            ]
        );

        $this->products->shouldBeLike([
            new ProductView('product-id1', 'Teapot'),
            new ProductView('product-id2', 'Iron'),
        ]);
    }

    public function it_can_be_empty()
    {
        $this->beConstructedWith('basket-id', []);

        $this->products->shouldBeLike([]);
    }
}
