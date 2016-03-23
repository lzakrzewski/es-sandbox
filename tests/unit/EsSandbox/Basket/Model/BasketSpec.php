<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\BasketWasPickedUp;
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

    public function it_can_be_reconstitute_from_history()
    {
        $basketId = BasketId::generate();

        $this->beConstructedThrough('reconstituteFrom', [AggregateHistory::of($basketId, [new BasketWasPickedUp($basketId)])]);

        $this->shouldBeLike(Basket::pickUp($basketId));
    }

    public function it_has_count_of_products()
    {
        $basketId = BasketId::generate();

        $this->beConstructedThrough('pickUp', [$basketId]);

        $this->count()->shouldBe(0);
    }
}
