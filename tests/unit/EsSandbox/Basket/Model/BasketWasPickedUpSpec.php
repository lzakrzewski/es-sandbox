<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @mixin BasketWasPickedUp
 */
class BasketWasPickedUpSpec extends ObjectBehavior
{
    public function it_can_be_string()
    {
        $this->beConstructedWith(Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'));

        $this
            ->toArray()
            ->shouldBe(
                [
                    'basketId' => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                ]
            );
    }

    public function it_can_be_created_from_string()
    {
        $this->beConstructedThrough(
            'fromArray',
            [
                [
                    'basketId' => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                ],
            ]
        );

        $this->shouldBeLike(new BasketWasPickedUp(Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1')));
    }
}
