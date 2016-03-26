<?php

namespace EsSandbox\Basket\Application\Projection;

final class ProductView
{
    /** @var string */
    public $productId;

    /** @var string */
    public $name;

    /**
     * @param string $productId
     * @param string $name
     */
    public function __construct($productId, $name)
    {
        $this->productId = $productId;
        $this->name      = $name;
    }
}
