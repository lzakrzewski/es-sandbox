<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Common\Application\CommandBus\CommandHandler;
use EsSandbox\Common\Model\EventStore;

final class RemoveProductFromBasketHandler implements CommandHandler
{
    /** @var EventStore */
    private $eventStore;

    /**
     * @param EventStore $events
     */
    public function __construct(EventStore $events)
    {
        $this->eventStore = $events;
    }

    /**
     * @param RemoveProductFromBasket $command
     */
    public function handle(RemoveProductFromBasket $command)
    {
        $basketId = $command->basketId();
        $basket   = Basket::reconstituteFrom(
            $this->eventStore->aggregateHistoryFor($basketId)
        );

        $basket->removeProduct($command->productId);

        $this->eventStore->commit($basket->uncommittedEvents());
    }
}
