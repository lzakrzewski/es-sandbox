<?php

namespace tests\integration\EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Bundle\AppBundle\Command\GetProjectionCommand;
use Ramsey\Uuid\Uuid;
use tests\integration\CLITestCase;

class GetProjectionCommandTest extends CLITestCase
{
    /** @test */
    public function it_renders_projection()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Teapot'),
        ]);

        $this->executeCommand(new GetProjectionCommand(), ['basketId' => (string) $basketId]);

        $this->outputShouldStatusCodeIs(0);
    }

    /** @test */
    public function it_renders_projection_without_any_events()
    {
        $basketId = Uuid::uuid4();

        $this->executeCommand(new GetProjectionCommand(), ['basketId' => (string) $basketId]);

        $this->outputShouldStatusCodeIs(0);
    }
}
