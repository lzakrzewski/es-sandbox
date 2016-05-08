<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BasketWasPickedUp implements Event
{
    /** @var UuidInterface */
    private $basketId;

    /**
     * @param UuidInterface $basketId
     */
    public function __construct(UuidInterface $basketId)
    {
        $this->basketId = $basketId;
    }

    /** {@inheritdoc} */
    public function id()
    {
        return $this->basketId;
    }

    /** {@inheritdoc} */
    public function toArray()
    {
        return ['basketId' => (string) $this->basketId];
    }

    /** {@inheritdoc} */
    public static function fromArray(array $contents)
    {
        return new self(Uuid::fromString($contents['basketId']));
    }
}
