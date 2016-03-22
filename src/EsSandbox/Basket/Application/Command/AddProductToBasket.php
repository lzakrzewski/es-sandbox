<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\UuidInterface;

final class AddProductToBasket implements Command
{
    /** @var UuidInterface */
    public $basketId;

    /** @var UuidInterface */
    public $productId;

    /** @var string */
    public $name;

    /**
     * @param UuidInterface $basketId
     * @param UuidInterface $productId
     * @param               $name
     */
    public function __construct(UuidInterface $basketId, UuidInterface $productId, $name)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
        $this->name      = $name;
    }

    /** {@inheritdoc} */
    public function id()
    {
        return $this->basketId;
    }
}
