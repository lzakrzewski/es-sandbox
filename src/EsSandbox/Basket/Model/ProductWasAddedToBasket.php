<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProductWasAddedToBasket implements Event
{
    /** @var UuidInterface */
    private $basketId;

    /** @var UuidInterface */
    private $productId;

    /** @var string */
    private $name;

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

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /** {@inheritdoc} */
    public function toArray()
    {
        return [
            'basketId'  => (string) $this->basketId,
            'productId' => (string) $this->productId,
            'name'      => $this->name,
        ];
    }

    /** {@inheritdoc} */
    public static function fromArray(array $contents)
    {
        return new self(
            Uuid::fromString($contents['basketId']),
            Uuid::fromString($contents['productId']),
            $contents['name']
        );
    }
}
