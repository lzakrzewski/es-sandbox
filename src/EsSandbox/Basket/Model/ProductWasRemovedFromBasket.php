<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProductWasRemovedFromBasket implements Event
{
    /** @var UuidInterface */
    private $basketId;

    /** @var UuidInterface */
    private $productId;

    /**
     * @param UuidInterface $basketId
     * @param UuidInterface $productId
     */
    public function __construct(UuidInterface $basketId, UuidInterface $productId)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
    }

    /**
     * @return UuidInterface
     */
    public function id()
    {
        return $this->basketId;
    }

    /**
     * @return UuidInterface
     */
    public function productId()
    {
        return $this->productId;
    }

    /** {@inheritdoc} */
    public function __toString()
    {
        return (string) json_encode(
            [
                'basketId'  => (string) $this->basketId,
                'productId' => (string) $this->productId,
            ]
        );
    }

    /** {@inheritdoc} */
    public static function fromString($contents)
    {
        $contents = json_decode($contents);

        return new self(
            Uuid::fromString($contents->basketId),
            Uuid::fromString($contents->productId)
        );
    }
}
