<?php

namespace tests\contexts;

use Assert\Assertion;
use Behat\Gherkin\Node\TableNode;
use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductDoesNotExist;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class BasketContext extends DefaultContext
{
    /** @var BasketId */
    private $basketId;

    /** @AfterScenario */
    public function afterScenario()
    {
        $this->basketId = null;
    }

    /**
     * @Given I don't have basket
     */
    public function iDonTHaveBasket()
    {
    }

    /**
     * @Given I have basket with id :basketId picked up
     */
    public function iHaveBasketWithIdPickedUp(BasketId $basketId)
    {
        $this->basketId = $basketId;

        $this->given(new BasketWasPickedUp($this->basketId));
    }

    /**
     * @Given My basket contains products:
     */
    public function myBasketContainsProducts(TableNode $table)
    {
        $products = $table->getTable();

        array_shift($products);

        foreach ($products as $product) {
            $this->given(new ProductWasAddedToBasket($this->basketId, ProductId::fromString($product[0]), $product[1]));
        }
    }

    /**
     * @When I pick up basket with id :basketId
     */
    public function iPickUpBasketWithId(BasketId $basketId)
    {
        $this->basketId = $basketId;

        $this->when(new PickUpBasket($basketId->raw()));
    }

    /**
     * @When I add product with id :productId and name :name to my basket
     */
    public function iAddProductWithIdAndNameToMyBasket(ProductId $productId, $name)
    {
        $this->when(new AddProductToBasket($this->basketId->raw(), $productId->raw(), $name));
    }

    /**
     * @When I remove product with id :productId from my basket
     */
    public function iRemoveProductWithIdFromMyBasket(ProductId $productId)
    {
        $this->when(new RemoveProductFromBasket($this->basketId->raw(), $productId->raw()));
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
            $this->container()->get('es_sandbox.event_store')->aggregateHistoryFor($this->basketId)
        );

        Assertion::eq($count, $basket->count());
    }
}
