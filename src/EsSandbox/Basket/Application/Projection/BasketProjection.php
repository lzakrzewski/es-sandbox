<?php

namespace EsSandbox\Basket\Application\Projection;

use Ramsey\Uuid\UuidInterface;

interface BasketProjection
{
    /**
     * @param UuidInterface $basketId
     *
     * @return array
     */
    public function get(UuidInterface $basketId);
}
