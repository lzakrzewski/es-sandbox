<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\UuidInterface;

final class PickUpBasket implements Command
{
    /** @var UuidInterface */
    public $basketId;

    /**
     * @param UuidInterface $basketId
     */
    public function __construct(UuidInterface $basketId)
    {
        $this->basketId = $basketId;
    }

    /**
     * @return UuidInterface
     */
    public function id()
    {
        return $this->basketId;
    }
}
