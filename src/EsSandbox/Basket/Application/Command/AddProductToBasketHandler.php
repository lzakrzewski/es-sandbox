<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Common\Application\CommandBus\CommandHandler;

final class AddProductToBasketHandler implements CommandHandler
{
    private $history;

    public function handle(AddProductToBasket $command)
    {
        $basket = $this->history->get($command->basketId);
        $basket->addProduct();
    }
}
