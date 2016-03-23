<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Common\Application\CommandBus\CommandHandler;
use EsSandbox\Common\Model\EventStore;

final class RemoveProductFromBasketHandler implements CommandHandler
{
    /** @var EventStore */
    private $events;

    /**
     * @param EventStore $events
     */
    public function __construct(EventStore $events)
    {
        $this->events = $events;
    }

    /**
     * @param RemoveProductFromBasket $command
     */
    public function handle(RemoveProductFromBasket $command)
    {
        $basketId = BasketId::of($command->id());

        $basket = Basket::reconstituteFrom($this->events->aggregateHistoryFor($basketId));
        $basket->removeProduct(ProductId::of($command->productId));
    }
}
