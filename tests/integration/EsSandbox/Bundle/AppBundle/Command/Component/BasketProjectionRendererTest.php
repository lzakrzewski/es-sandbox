<?php

namespace tests\integration\EsSandbox\Bundle\AppBundle\Command\Component;

use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Application\Projection\ProductView;
use EsSandbox\Bundle\AppBundle\Command\Component\BasketProjectionRenderer;

class BasketProjectionRendererTest extends RendererTestCase
{
    /** @var BasketProjectionRenderer */
    private $renderer;

    /** @test */
    public function it_renders_basket_view()
    {
        $basketView = new BasketView(
            'basket-id',
            [
                new ProductView('product-id1', 'Teapot'),
                new ProductView('product-id2', 'Iron'),
            ]
        );

        $this->renderer->render($this->output(), $basketView);

        $this->assertThatDisplayContains('Your basket contains:');
        $this->assertThatDisplayContains('product-id1');
        $this->assertThatDisplayContains('product-id2');
    }

    /** @test */
    public function it_renders_empty_basket()
    {
        $basketView = new BasketView('basket-id', []);

        $this->renderer->render($this->output(), $basketView);

        $this->assertThatDisplayContains('Your basket is empty.');
    }

    /** @test */
    public function it_notifies_that_basket_has_not_been_picked_up()
    {
        $this->renderer->render($this->output(), null);

        $this->assertThatDisplayContains('Your basket has not been picked up.');
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->renderer = $this->container()
            ->get('es_sandbox.bundle.command.component.basket_projection_renderer');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->renderer = null;

        parent::tearDown();
    }
}
