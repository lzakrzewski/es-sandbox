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

    /** {@inheritdoc} */
    public function __toString()
    {
        return (string) json_encode(['basketId' => (string) $this->basketId]);
    }

    /** {@inheritdoc} */
    public static function fromString($contents)
    {
        return new self(BasketId::fromString(json_decode($contents)->basketId));
    }
}
