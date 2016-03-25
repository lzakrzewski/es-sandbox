<?php

namespace EsSandbox\Basket\Application\Projection;

use Ramsey\Uuid\UuidInterface;

interface BasketProjection
{
    /**
     * @param UuidInterface $basketId
     *
     * @return ProductView[]
     */
    public function get(UuidInterface $basketId);
}
