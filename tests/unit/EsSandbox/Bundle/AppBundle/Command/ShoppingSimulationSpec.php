<?php

namespace tests\unit\EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Bundle\AppBundle\Command\ShoppingSimulation;
use EsSandbox\Common\Application\CommandBus\Command;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;

/**
 * @mixin ShoppingSimulation
 */
class ShoppingSimulationSpec extends ObjectBehavior
{
    public function it_simulates_shopping()
    {
        $basketId = Uuid::uuid4();

        $this->beConstructedThrough('simulate', ['basketId' => $basketId, 'limit' => 10]);

        $this->get()->shouldHaveProductsCount(10);
    }

    public function it_fails_when_limit_is_invalid()
    {
        $basketId = Uuid::uuid4();

        $this->beConstructedThrough('simulate', ['basketId' => $basketId, 'limit' => -10]);

        $this->shouldThrow(\InvalidArgumentException::class)->during('get');
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
        ];
    }
}
