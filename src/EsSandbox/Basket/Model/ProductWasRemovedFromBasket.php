<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventWithShortNameAsType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProductWasRemovedFromBasket implements Event
{
    use EventWithShortNameAsType;

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

    /** {@inheritdoc} */
    public static function fromData(array $data)
    {
        return new self(
            Uuid::fromString($data['basketId']),
            Uuid::fromString($data['productId'])
        );
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
    public function data()
    {
        return [
            'basketId'  => (string) $this->basketId,
            'productId' => (string) $this->productId,
        ];
    }
}
