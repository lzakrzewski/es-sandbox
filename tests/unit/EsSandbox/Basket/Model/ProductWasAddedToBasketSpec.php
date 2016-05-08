<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @mixin ProductWasAddedToBasket
 */
class ProductWasAddedToBasketSpec extends ObjectBehavior
{
    public function it_can_be_string()
    {
        $this->beConstructedWith(
            Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
            Uuid::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d'),
            'Teapot'
        );

        $this
            ->toArray()
            ->shouldBe(
                [
                    'basketId'  => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                    'productId' => '03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d',
                    'name'      => 'Teapot',
                ]
            );
    }

    public function it_can_be_created_from_string()
    {
        $this->beConstructedThrough(
            'fromArray',
            [
                [
                    'basketId'  => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                    'productId' => '03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d',
                    'name'      => 'Teapot',
                ],
            ]
        );

        $this->shouldBeLike(
            new ProductWasAddedToBasket(
                Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
                Uuid::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d'),
                'Teapot'
            )
        );
    }
}
