<?php

namespace tests\integration\EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Bundle\AppBundle\Command\RenderBasketProjectionCommand;
use Ramsey\Uuid\Uuid;
use tests\integration\CLITestCase;

class RenderBasketProjectionCommandTest extends CLITestCase
{
    /** @test */
    public function it_renders_basket_projection()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Teapot'),
        ]);

        $this->executeCommand(new RenderBasketProjectionCommand(), ['basketId' => (string) $basketId, 'engine' => 'event-store']);

        $this->outputShouldStatusCodeIs(0);
    }

    /** @test */
    public function it_renders_basket_projection_with_default_arguments()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Teapot'),
        ]);

        $this->executeCommand(new RenderBasketProjectionCommand());

        $this->outputShouldStatusCodeIs(0);
    }

    /** @test */
    public function it_renders_basket_projection_with_custom_basket_id()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Teapot'),
        ]);

        $this->executeCommand(new RenderBasketProjectionCommand(), ['basketId' => (string) $basketId]);

        $this->outputShouldStatusCodeIs(0);
    }

    /** @test */
    public function it_renders_basket_projection_with_engine_event_store()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Teapot'),
        ]);

        $this->executeCommand(new RenderBasketProjectionCommand(), ['engine' => 'event-store']);

        $this->outputShouldStatusCodeIs(0);
    }

    /** @test */
    public function it_renders_basket_projection_with_engine_mysql()
    {
        $basketId = Uuid::uuid4();

        $this->given([
            new BasketWasPickedUp($basketId),
            new ProductWasAddedToBasket($basketId, Uuid::uuid4(), 'Teapot'),
        ]);

        $this->executeCommand(new RenderBasketProjectionCommand(), ['engine' => 'mysql']);

        $this->outputShouldStatusCodeIs(0);
    }

    /** @test */
    public function it_fails_when_basket_id_is_invalid()
    {
        $this->executeCommand(new RenderBasketProjectionCommand(), ['basketId' => 'xyz']);

        $this->outputShouldStatusCodeIs(1);
    }

    /** @test */
    public function it_renders_basket_projection_without_any_events()
    {
        $this->executeCommand(new RenderBasketProjectionCommand(), ['basketId' => Uuid::uuid4()->toString()]);

        $this->outputShouldStatusCodeIs(0);
    }
}
