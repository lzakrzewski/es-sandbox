<?php

namespace EsSandbox\Basket\Application\Projection;

final class ProductView
{
    /** @var string */
    private $productId;

    /** @var string */
    private $name;

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
