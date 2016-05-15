<?php

namespace EsSandbox\Basket\Application\Projection;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Application\CommandBus\EventSubscriber;

interface BasketProjector extends EventSubscriber
{
    /**
     * @param BasketWasPickedUp $event
     */
    public function applyBasketWasPickedUp(BasketWasPickedUp $event);

    /**
     * @param ProductWasAddedToBasket $event
     */
    public function applyProductWasAddedToBasket(ProductWasAddedToBasket $event);

    /**
     * @param ProductWasRemovedFromBasket $event
     */
    public function applyProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event);
}
