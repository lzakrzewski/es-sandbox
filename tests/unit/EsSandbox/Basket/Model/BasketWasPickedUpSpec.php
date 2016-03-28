<?php

namespace tests\unit\EsSandbox\Basket\Model;

use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use PhpSpec\ObjectBehavior;

/**
 * @mixin BasketWasPickedUp
 */
class BasketWasPickedUpSpec extends ObjectBehavior
{
    public function it_can_be_string()
    {
        $this->beConstructedWith(BasketId::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1'));

        $this->__toString()->shouldBe(json_encode(['basketId' => '44f80bab-a9eb-447a-bea9-4611d09a6bd1']));
    }

    public function it_can_be_created_from_string()
    {
        $this->beConstructedThrough('fromString', [json_encode(['basketId' => '44f80bab-a9eb-447a-bea9-4611d09a6bd1'])]);

        $this->shouldBeLike(new BasketWasPickedUp(BasketId::fromString('44f80bab-a9eb-447a-bea9-4611d09a6bd1')));
    }
}
