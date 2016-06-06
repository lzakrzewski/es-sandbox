<?php

namespace tests\integration\EsSandbox\Bundle\AppBundle\Command\Component;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Bundle\AppBundle\Command\Component\BasketHistoryRenderer;
use EsSandbox\Common\Model\AggregateHistory;
use Ramsey\Uuid\Uuid;

class BasketHistoryRendererTest extends RendererTestCase
{
    /** @var BasketHistoryRenderer */
    private $renderer;

    /** @test */
    public function it_renders_history_of_basket_aggregate()
    {
        $basketId   = Uuid::uuid4();
        $productId1 = Uuid::uuid4();
        $productId2 = Uuid::uuid4();

        $history = new AggregateHistory(
            [
                new BasketWasPickedUp($basketId),
                new ProductWasAddedToBasket($basketId, $productId1, 'Teapot'),
                new ProductWasAddedToBasket($basketId, $productId2, 'Iron'),
                new ProductWasRemovedFromBasket($basketId, $productId2, 'Iron'),
            ]
        );

        $this->renderer->render($this->output(), $history);

        $this->assertThatDisplayContains('BasketWasPickedUp');
        $this->assertThatDisplayContains((string) $productId1);
        $this->assertThatDisplayContains('ProductWasAddedToBasket');
        $this->assertThatDisplayContains((string) $productId2);
        $this->assertThatDisplayContains('ProductWasRemovedFromBasket');
    }

    /** @test */
    public function it_renders_history_of_empty_basket()
    {
        $basketId = Uuid::uuid4();

        $history = new AggregateHistory([new BasketWasPickedUp($basketId)]);

        $this->renderer->render($this->output(), $history);

        $this->assertThatDisplayContains('Your basket is empty.');
    }

    /** @test */
    public function it_notifies_that_basket_has_not_been_picked_up()
    {
        $history = new AggregateHistory([]);

        $this->renderer->render($this->output(), $history);

        $this->assertThatDisplayContains('Your basket has not been picked up.');
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->renderer = $this->container()
            ->get('es_sandbox.bundle.command.component.basket_events_renderer');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->renderer = null;

        parent::tearDown();
    }
}
