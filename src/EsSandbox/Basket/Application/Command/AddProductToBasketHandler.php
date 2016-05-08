<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Common\Application\CommandBus\CommandHandler;
use EsSandbox\Common\Model\EventStore;

final class AddProductToBasketHandler implements CommandHandler
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
     * @param AddProductToBasket $command
     */
    public function handle(AddProductToBasket $command)
    {
        $basketId = $command->basketId();
        $basket   = Basket::reconstituteFrom(
            $this->eventStore->aggregateHistoryFor($basketId)
        );

        $basket->addProduct($command->productId, $command->name);

        $this->eventStore->commit($basket->uncommittedEvents());
    }
}
