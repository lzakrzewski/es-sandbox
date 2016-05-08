<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Common\Application\CommandBus\CommandHandler;
use EsSandbox\Common\Model\EventStore;

final class PickUpBasketHandler implements CommandHandler
{
    /** @var EventStore */
    private $eventStore;

    /**
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;
    }

    /**
     * @param PickUpBasket $command
     */
    public function handle(PickUpBasket $command)
    {
        $basket = Basket::pickUp($command->basketId);

        $this->eventStore->commit($basket->uncommittedEvents());
    }
}
