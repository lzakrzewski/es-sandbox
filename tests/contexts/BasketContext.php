<?php

namespace tests\contexts;

use Assert\Assertion;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use tests\builders\PersistedBuilderDictionary;

class BasketContext extends DefaultContext
{
    use PersistedBuilderDictionary;

    /** @var BasketId */
    private $basketId;

    /**
     * @Given I don't have basket
     */
    public function iDonTHaveBasket()
    {
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
     * @Then I should be notified that was picked up
     */
    public function iShouldBeNotifiedThatWasPickedUp()
    {
        $this->then(BasketWasPickedUp::class);
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
