<?php

namespace tests\contexts;

use tests\builders\PersistedBuilderDictionary;
use tests\common\CommandBusDictionary;

class BasketContext extends DefaultContext
{
    use PersistedBuilderDictionary;
    use CommandBusDictionary;

    /**
     * @Given My basket is empty
     */
    public function myBasketIsEmpty()
    {

    }

    /**
     * @When I add product with id :arg1 and name :arg2
     */
    public function iAddProductWithIdAndName($arg1, $arg2)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that product was added to basket
     */
    public function iShouldBeNotifiedThatProductWasAddedToBasket()
    {
        throw new PendingException();
    }

    /**
     * @Then My basket should contain :arg1 products
     */
    public function myBasketShouldContainProducts($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Given My basket contains products:
     */
    public function myBasketContainsProducts(TableNode $table)
    {
        throw new PendingException();
    }

    /**
     * @When I remove product with :arg1
     */
    public function iRemoveProductWith($arg1)
    {
        throw new PendingException();
    }

    /**
     * @Then I should be notified that product was removed from basket
     */
    public function iShouldBeNotifiedThatProductWasRemovedFromBasket()
    {
        throw new PendingException();
    }

    /**
     * @When I view my basket
     */
    public function iViewMyBasket()
    {
        throw new PendingException();
    }

    /**
     * @Then I should see:
     */
    public function iShouldSee(TableNode $table)
    {
        throw new PendingException();
    }


}
