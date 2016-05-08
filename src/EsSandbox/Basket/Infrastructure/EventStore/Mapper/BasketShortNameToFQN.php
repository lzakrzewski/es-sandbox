<?php

namespace EsSandbox\Basket\Infrastructure\EventStore\Mapper;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Infrastructure\EventStore\Mapper\ShortNameToFQN;
use EsSandbox\Common\Model\Event;

final class BasketShortNameToFQN implements ShortNameToFQN
{
    /** @var Event[] */
    private $events = [
        BasketWasPickedUp::class,
        ProductWasAddedToBasket::class,
        ProductWasRemovedFromBasket::class,
    ];

    /** @var array */
    private $map = [];

    /** {@inheritdoc} */
    public function get($shortName)
    {
        if (empty($this->map)) {
            $this->map = $this->createMap();
        }

        if (isset($this->map[$shortName])) {
            return $this->map[$shortName];
        }

        throw new \InvalidArgumentException(sprintf('There is no fqn for %s short name.', $shortName));
    }

    private function createMap()
    {
        $map = [];

        foreach ($this->events as $eventClass) {
            $reflection                       = new \ReflectionClass($eventClass);
            $map[$reflection->getShortName()] = $eventClass;
        }

        return $map;
    }
}
