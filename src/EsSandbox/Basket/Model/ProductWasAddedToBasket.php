<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventWithShortNameAsType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class ProductWasAddedToBasket implements Event
{
    use EventWithShortNameAsType;

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
    public function data()
    {
        return [
            'basketId'  => (string) $this->basketId,
            'productId' => (string) $this->productId,
            'name'      => $this->name,
        ];
    }

    /** {@inheritdoc} */
    public static function fromData(array $data)
    {
        return new self(
            Uuid::fromString($data['basketId']),
            Uuid::fromString($data['productId']),
            $data['name']
        );
    }
}
