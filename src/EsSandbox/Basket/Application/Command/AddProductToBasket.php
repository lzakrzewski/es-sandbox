<?php

namespace EsSandbox\Basket\Application\Command;

use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\UuidInterface;

final class AddProductToBasket implements Command
{
    /** @var UuidInterface @deprecated */
    public $basketId;

    /** @var UuidInterface @deprecated */
    public $productId;

    /** @var string */
    public $name;

    /**
     * @param UuidInterface $basketId
     * @param UuidInterface $productId
     * @param string        $name
     */
    public function __construct(UuidInterface $basketId, UuidInterface $productId, $name)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
        $this->name      = $name;
    }

    /** {@inheritdoc} */
    public function basketId()
    {
        return $this->basketId;
    }
}
