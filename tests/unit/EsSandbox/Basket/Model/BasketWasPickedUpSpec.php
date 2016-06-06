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
    public function it_has_id()
    {
        $this->beConstructedWith(
            Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1')
        );

        $this
            ->id()
            ->shouldBeLike(Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'));
    }

    public function it_has_type()
    {
        $this->beConstructedWith(
            Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1')
        );

        $this
            ->type()
            ->shouldBe('BasketWasPickedUp');
    }

    public function it_has_data()
    {
        $this->beConstructedWith(
            Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1')
        );

        $this
            ->data()
            ->shouldBe(
                [
                    'basketId' => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                ]
            );
    }

    public function it_can_be_created_from_data()
    {
        $this->beConstructedThrough(
            'fromData',
            [
                [
                    'basketId' => '44f80bab-a9eb-447a-bea9-4611d09a6bd1',
                ],
            ]
        );

        $this->shouldBeLike(new BasketWasPickedUp(Uuid::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1')));
    }
}
