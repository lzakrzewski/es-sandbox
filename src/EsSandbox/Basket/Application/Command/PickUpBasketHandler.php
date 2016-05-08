<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Common\Application\CommandBus\CommandHandler;

final class PickUpBasketHandler implements CommandHandler
{
    /**
     * @param PickUpBasket $command
     */
    public function handle(PickUpBasket $command)
    {
        Basket::pickUp($command->basketId());
    }
}
