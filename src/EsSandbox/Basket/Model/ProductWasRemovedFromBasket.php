<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;

final class ProductWasRemovedFromBasket implements Event
{
    /** @var BasketId */
    private $basketId;

    /** @var ProductId */
    private $productId;

    /**
     * @param BasketId  $basketId
     * @param ProductId $productId
     */
    public function __construct(BasketId $basketId, ProductId $productId)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
    }

    /**
     * @return BasketId
     */
    public function id()
    {
        return $this->basketId;
    }

    /**
     * @return ProductId
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

        return new self(BasketId::fromString($contents->basketId), ProductId::fromString($contents->productId));
    }
}
