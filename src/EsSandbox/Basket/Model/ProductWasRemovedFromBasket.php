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
}
