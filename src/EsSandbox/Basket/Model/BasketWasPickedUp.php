<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;

final class BasketWasPickedUp implements Event
{
    /** @var BasketId */
    private $basketId;

    /**
     * @param BasketId $basketId
     */
    public function __construct(BasketId $basketId)
    {
        $this->basketId = $basketId;
    }

    /** {@inheritdoc} */
    public function id()
    {
        return $this->basketId;
    }
}
