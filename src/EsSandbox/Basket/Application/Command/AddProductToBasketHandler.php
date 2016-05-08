<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Common\Application\CommandBus\CommandHandler;
use EsSandbox\Common\Model\EventStore;

final class AddProductToBasketHandler implements CommandHandler
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
     * @param AddProductToBasket $command
     */
    public function handle(AddProductToBasket $command)
    {
        $basketId = $command->basketId();

        $basket = Basket::reconstituteFrom($this->events->aggregateHistoryFor($basketId));
        $basket->addProduct($command->productId, $command->name);
    }
}
