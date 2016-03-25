<?php

namespace tests\integration\EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Bundle\AppBundle\Command\SimulateShoppingCommand;
use EsSandbox\Common\Infrastructure\EventStore\InMemoryEventStore;
use EsSandbox\Common\Model\AggregateHistory;
use tests\integration\CLITestCase;

class SimulateShoppingCommandTest extends CLITestCase
{
    /** @test */
    public function it_simulates_shopping_with_default_limit_of_products()
    {
        $this->executeCommand(new SimulateShoppingCommand());

        $this->outputShouldStatusCodeIs(0);
        $this->countProducts(SimulateShoppingCommand::DEFAULT_LIMIT_OF_PRODUCTS);
    }

    /** @test */
    public function it_fails_when_limit_of_products_is_negative()
    {
        $this->executeCommand(new SimulateShoppingCommand(), ['limit' => -1]);

        $this->outputShouldStatusCodeIs(1);
    }

    /** @test */
    public function it_simulates_shopping_with_custom_limit_of_products()
    {
        $this->executeCommand(new SimulateShoppingCommand());

        $this->outputShouldStatusCodeIs(0);
        $this->countProducts(10);
    }

    //Todo: do it better
    private function countProducts($products)
    {
        $events = $this->container()->get('es_sandbox.event_store')->events();

        $this->assertEquals($products, Basket::reconstituteFrom(AggregateHistory::of(BasketId::generate(), $events))->count());
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        InMemoryStorage::instance()->clear();
        InMemoryEventStore::instance()->reset();
    }
}
