<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection\Dictionary;

use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Application\Projection\ProductView;

trait BasketProjectionDictionary
{
    private function assertThatBasketViewEquals(BasketView $expectedBasketView, $basketView)
    {
        $this->assertInstanceOf(BasketView::class, $basketView);
        $this->assertEquals($expectedBasketView->basketId, $basketView->basketId);
        $this->assertCount(count($expectedBasketView->products), $basketView->products);

        foreach ($expectedBasketView->products as $key => $productView) {
            $this->assertInstanceOf(ProductView::class, $basketView->products[$key]);
            $this->assertEquals($productView->productId, $basketView->products[$key]->productId);
            $this->assertEquals($productView->name, $basketView->products[$key]->name);
        }
    }
}
