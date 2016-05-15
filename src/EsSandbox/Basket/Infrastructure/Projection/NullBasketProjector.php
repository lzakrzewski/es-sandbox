<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketProjector;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;

class NullBasketProjector implements BasketProjector
{
    /** {@inheritdoc} */
    public function applyBasketWasPickedUp(BasketWasPickedUp $event)
    {
    }

    /** {@inheritdoc} */
    public function applyProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
    }

    /** {@inheritdoc} */
    public function applyProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
    }
}
