<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\UuidInterface;

final class RemoveProductFromBasket implements Command
{
    /** @var UuidInterface */
    public $basketId;

    /** @var UuidInterface */
    public $productId;

    /**
     * @param UuidInterface $basketId
     * @param UuidInterface $productId
     */
    public function __construct(UuidInterface $basketId, UuidInterface $productId)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
    }
}
