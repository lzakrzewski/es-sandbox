<?php

namespace tests\unit\EsSandbox\Basket\Infrastructure\EventStore\Mapper;

use EsSandbox\Basket\Infrastructure\EventStore\Mapper\BasketShortNameToFQN;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use PhpSpec\ObjectBehavior;

/**
 * @mixin BasketShortNameToFQN
 */
class BasketShortNameToFQNSpec extends ObjectBehavior
{
    public function it_gets_fqn_from_short_name()
    {
        $this->beAnInstanceOf(BasketShortNameToFQN::class);

        $this->get('BasketWasPickedUp')->shouldReturn(BasketWasPickedUp::class);
        $this->get('ProductWasAddedToBasket')->shouldReturn(ProductWasAddedToBasket::class);
        $this->get('ProductWasRemovedFromBasket')->shouldReturn(ProductWasRemovedFromBasket::class);
    }

    public function it_fails_when_there_is_no_fqn_for_short_name()
    {
        $this->beAnInstanceOf(BasketShortNameToFQN::class);

        $this->shouldThrow(\InvalidArgumentException::class)
            ->during('get', ['unknown']);
    }
}
