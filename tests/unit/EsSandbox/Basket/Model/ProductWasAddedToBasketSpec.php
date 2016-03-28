<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use PhpSpec\ObjectBehavior;

/**
 * @mixin ProductWasAddedToBasket
 */
class ProductWasAddedToBasketSpec extends ObjectBehavior
{
    public function it_can_be_string()
    {
        $this->beConstructedWith(
            BasketId::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
            ProductId::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d'),
            'Teapot'
        );

        $this->__toString()->shouldBe(
            json_encode(
                [
                    'basketId'  => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                    'productId' => '03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d',
                    'name'      => 'Teapot',
                ]
            )
        );
    }

    public function it_can_be_created_from_string()
    {
        $this->beConstructedThrough('fromString', [
            json_encode([
                'basketId'  => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                'productId' => '03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d',
                'name'      => 'Teapot',
            ]),
        ]);

        $this->shouldBeLike(
            new ProductWasAddedToBasket(
                BasketId::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
                ProductId::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d'),
                'Teapot'
            )
        );
    }
}
