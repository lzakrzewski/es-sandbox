<?php

namespace tests\contexts;

use Assert\Assertion;
use Behat\Gherkin\Node\TableNode;
use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductDoesNotExist;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BasketContext extends DefaultContext
{
    /**
     * @Given I don't have basket
     */
    public function iDonTHaveBasket()
    {
    }

    /**
     * @Given I have basket with id :basketId picked up
     */
    public function iHaveBasketWithIdPickedUp(UuidInterface $basketId)
    {
        $this->aggregateId = $basketId;

        $this->given([new BasketWasPickedUp($this->aggregateId)]);
    }

    /**
     * @Given My basket contains products:
     */
    public function myBasketContainsProducts(TableNode $table)
    {
        $products = $table->getTable();

        array_shift($products);

        $this->given(array_map(function (array $productData) {
            return new ProductWasAddedToBasket(
                $this->aggregateId,
                Uuid::fromString($productData[0]),
                $productData[1]
            );
        }, $products));
    }

    /**
     * @When I pick up basket with id :basketId
     */
    public function iPickUpBasketWithId(UuidInterface $basketId)
    {
        $this->aggregateId = $basketId;

        $this->when(new PickUpBasket($basketId));
    }

    /**
     * @When I add product with id :productId and name :name to my basket
     */
    public function iAddProductWithIdAndNameToMyBasket(UuidInterface $productId, $name)
    {
        $this->when(new AddProductToBasket($this->aggregateId, $productId, $name));
    }

    /**
     * @When I remove product with id :productId from my basket
     */
    public function iRemoveProductWithIdFromMyBasket(UuidInterface $productId)
    {
        $this->when(new RemoveProductFromBasket($this->aggregateId, $productId));
    }

    /**
     * @Then I should be notified that was picked up
     */
    public function iShouldBeNotifiedThatWasPickedUp()
    {
        $this->then(BasketWasPickedUp::class);
    }

    /**
     * @Then I should be notified that product was added to basket
     */
    public function iShouldBeNotifiedThatProductWasAddedToBasket()
    {
        $this->then(ProductWasAddedToBasket::class);
    }

    /**
     * @Then I should be notified that product was removed from basket
     */
    public function iShouldBeNotifiedThatProductWasRemovedFromBasket()
    {
        $this->then(ProductWasRemovedFromBasket::class);
    }

    /**
     * @Then I should be notified that product does not exists
     */
    public function iShouldBeNotifiedThatProductDoesNotExists()
    {
        $this->expectException(ProductDoesNotExist::class);
    }

    /**
     * @Then My basket should contain :count products
     */
    public function myBasketShouldContainProducts($count)
    {
        $basket = Basket::reconstituteFrom(
            $this->container()
                ->get('es_sandbox.event_store')
                ->aggregateHistoryFor($this->aggregateId)
        );

        Assertion::eq($count, $basket->count());
    }
}
