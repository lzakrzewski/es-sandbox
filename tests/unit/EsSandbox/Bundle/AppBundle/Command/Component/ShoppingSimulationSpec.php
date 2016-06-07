<?php

namespace tests\unit\EsSandbox\Bundle\AppBundle\Command\Component;

use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Bundle\AppBundle\Command\Component\ShoppingSimulation;
use EsSandbox\Common\Application\CommandBus\Command;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @mixin ShoppingSimulation
 */
class ShoppingSimulationSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beAnInstanceOf(ShoppingSimulation::class);
    }

    public function it_simulates_shopping()
    {
        $basketId = Uuid::uuid4();

        $this->simulate($basketId, 10)
            ->shouldHaveProductsCount(10);
    }

    public function it_has_30_percent_of_removing_product_commands()
    {
        $basketId = Uuid::uuid4();

        $commands = $this->simulate($basketId, 10);

        $commands->shouldHaveAddProductCommandsCount(13);
        $commands->shouldHaveRemoveProductCommandsCount(3);
        $commands->shouldHaveProductsCount(10);
    }

    public function it_simulates_shopping_with_wide_limit()
    {
        $basketId = Uuid::uuid4();

        $this->simulate($basketId, 1000)
            ->shouldHaveProductsCount(1000);
    }

    public function it_simulates_shopping_with_zero_limit()
    {
        $basketId = Uuid::uuid4();

        $this->simulate($basketId, 0)
            ->shouldHaveProductsCount(0);
    }

    public function it_fails_when_limit_is_invalid()
    {
        $basketId = Uuid::uuid4();

        $this->shouldThrow(\InvalidArgumentException::class)
            ->during('simulate', ['basketId' => $basketId, 'limit' => -10]);
    }

    public function getMatchers()
    {
        return [
            'haveProductsCount' => function (array $commands, $expectedCount) {
                $addProductCommands = array_filter($commands, function (Command $command) {
                    return $command instanceof AddProductToBasket;
                });

                $removeProductCommands = array_filter($commands, function (Command $command) {
                    return $command instanceof RemoveProductFromBasket;
                });

                return (count($addProductCommands) - count($removeProductCommands)) === $expectedCount;
            },
            'haveAddProductCommandsCount' => function (array $commands, $expectedCount) {
                $addProductCommands = array_filter($commands, function (Command $command) {
                    return $command instanceof AddProductToBasket;
                });

                return count($addProductCommands) === $expectedCount;
            },
            'haveRemoveProductCommandsCount' => function (array $commands, $expectedCount) {
                $removeProductCommands = array_filter($commands, function (Command $command) {
                    return $command instanceof RemoveProductFromBasket;
                });

                return count($removeProductCommands) === $expectedCount;
            },
        ];
    }
}
