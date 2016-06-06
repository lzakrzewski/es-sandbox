<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventWithShortNameAsType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class BasketWasPickedUp implements Event
{
    use EventWithShortNameAsType;

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
    public function data()
    {
        return ['basketId' => (string) $this->basketId];
    }

    /** {@inheritdoc} */
    public static function fromData(array $data)
    {
        return new self(Uuid::fromString($data['basketId']));
    }
}
