<?php

namespace tests\integration\EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Basket\Model\Basket;
use EsSandbox\Bundle\AppBundle\Command\SimulateShoppingCommand;
use EsSandbox\Common\Infrastructure\EventStore\InMemoryEventStore;
use EsSandbox\Common\Model\AggregateHistory;
use Ramsey\Uuid\Uuid;
use tests\integration\CLITestCase;

//Todo: better asserts
class SimulateShoppingCommandTest extends CLITestCase
{
    /** @test */
    public function it_simulates_shopping()
    {
        $basketId = Uuid::uuid4();

        $this->executeCommand(new SimulateShoppingCommand(), ['basketId' => (string) $basketId, 'limit' => 10]);

        $this->outputShouldStatusCodeIs(0);
        $this->countProducts(10);
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

    private function countProducts($products)
    {
        $events = $this->container()->get('es_sandbox.event_store')->events();

        $this->assertEquals($products, Basket::reconstituteFrom(AggregateHistory::of($events))->count());
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        InMemoryStorage::instance()->clear();
        InMemoryEventStore::instance()->reset();
    }
}
