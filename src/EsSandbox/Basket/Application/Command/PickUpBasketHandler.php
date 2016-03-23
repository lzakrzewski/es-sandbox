<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Common\Application\CommandBus\CommandHandler;

final class PickUpBasketHandler implements CommandHandler
{
    /**
     * @param PickUpBasket $command
     */
    public function handle(PickUpBasket $command)
    {
        Basket::pickUp(BasketId::of($command->id()));
    }
}
