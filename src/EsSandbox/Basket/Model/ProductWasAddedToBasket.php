<?php

namespace EsSandbox\Basket\Model;

use EsSandbox\Common\Model\Event;

final class ProductWasAddedToBasket implements Event
{
    /** @var BasketId */
    private $basketId;

    /** @var ProductId */
    private $productId;

    /** @var string */
    private $name;

    /**
     * @param BasketId  $basketId
     * @param ProductId $productId
     * @param string    $name
     */
    public function __construct(BasketId $basketId, ProductId $productId, $name)
    {
        $this->basketId  = $basketId;
        $this->productId = $productId;
        $this->name      = $name;
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

    /**
     * @return mixed
     */
    public function name()
    {
        return $this->name;
    }

    /** {@inheritdoc} */
    public function __toString()
    {
        return (string) json_encode(
            [
                'basketId'  => (string) $this->basketId,
                'productId' => (string) $this->productId,
                'name'      => $this->name,
            ]
        );
    }

    /** {@inheritdoc} */
    public static function fromString($contents)
    {
        $contents = json_decode($contents);

        return new self(BasketId::fromString($contents->basketId), ProductId::fromString($contents->productId), $contents->name);
    }
}
