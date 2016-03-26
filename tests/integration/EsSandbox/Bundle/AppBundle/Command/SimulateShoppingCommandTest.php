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
    public function it_simulates_shopping()
    {
        $basketId = BasketId::generate();

        $this->executeCommand(new SimulateShoppingCommand(), ['basketId' => (string) $basketId, 'limit' => 10]);

        $this->outputShouldStatusCodeIs(0);
        $this->countProducts($basketId, 10);
    }

    /** @test */
    public function it_simulates_shopping_with_default_values()
    {
        $this->executeCommand(new SimulateShoppingCommand());

        $this->outputShouldStatusCodeIs(0);
    }

    /** @test */
    public function it_fails_when_unexpected_error_occures()
    {
        $this->executeCommand(new SimulateShoppingCommand(), ['limit' => -1]);

        $this->outputShouldStatusCodeIs(1);
    }

    private function countProducts(BasketId $basketId, $products)
    {
        $events = $this->container()->get('es_sandbox.event_store')->events();

        $this->assertEquals($products, Basket::reconstituteFrom(AggregateHistory::of($basketId, $events))->count());
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        InMemoryStorage::instance()->clear();
        InMemoryEventStore::instance()->reset();
    }
}
