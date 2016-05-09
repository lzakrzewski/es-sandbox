<?php

namespace tests\integration\EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Bundle\AppBundle\Command\SimulateShoppingCommand;
use Ramsey\Uuid\Uuid;
use tests\integration\CLITestCase;

class SimulateShoppingCommandTest extends CLITestCase
{
    /** @test */
    public function it_simulates_shopping()
    {
        $basketId = Uuid::uuid4();

        $this->executeCommand(new SimulateShoppingCommand(), ['basketId' => (string) $basketId, 'limit' => 10]);

        $this->outputShouldStatusCodeIs(0);
        $this->countProductsInBasket($basketId, 10);
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

    private function countProductsInBasket($basketId, $expectedProductsCount)
    {
        $history = $this->container()->get('es_sandbox.event_store')->aggregateHistoryFor($basketId);

        $this->assertEquals($expectedProductsCount, Basket::reconstituteFrom($history)->count());
    }
}
