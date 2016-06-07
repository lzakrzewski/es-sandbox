<?php

namespace EsSandbox\Basket\Application\Projection;

use Ramsey\Uuid\UuidInterface;

interface LastBasketIdProjection
{
    /**
     * @return UuidInterface
     */
    public function get();
}
