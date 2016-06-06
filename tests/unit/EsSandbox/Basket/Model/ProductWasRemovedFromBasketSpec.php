<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @mixin ProductWasRemovedFromBasket
 */
class ProductWasRemovedFromBasketSpec extends ObjectBehavior
{
    public function it_has_id()
    {
        $this->beConstructedWith(
            Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
            Uuid::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d')
        );

        $this
            ->id()
            ->shouldBeLike(Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'));
    }

    public function it_has_type()
    {
        $this->beConstructedWith(
            Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
            Uuid::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d')
        );

        $this
            ->type()
            ->shouldBe('ProductWasRemovedFromBasket');
    }

    public function it_has_data()
    {
        $this->beConstructedWith(
            Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
            Uuid::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d')
        );

        $this
            ->data()
            ->shouldBe(
                [
                    'basketId'  => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                    'productId' => '03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d',
                ]
            );
    }

    public function it_can_be_created_from_data()
    {
        $this->beConstructedThrough('fromData', [
            [
                'basketId'  => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                'productId' => '03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d',
            ],
        ]);

        $this->shouldBeLike(
            new ProductWasRemovedFromBasket(
                Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'),
                Uuid::fromString('03a84dc9-5e37-4f9f-a0f0-b8efc5fe523d')
            )
        );
    }
}
