<?php

namespace EsSandbox\Bundle\AppBundle\Command\Component;

use Assert\Assertion;
use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ShoppingSimulation
{
    /** @var Command[] */
    private $commands = [];

    /** @var UuidInterface[] */
    private $products = [];

    /**
     * @param UuidInterface $basketId
     * @param int           $limit
     *
     * @return Command[]
     */
    public function simulate(UuidInterface $basketId, $limit)
    {
        return $this->shopping($basketId, $limit);
    }

    private function shopping(UuidInterface $basketId, $limit)
    {
        $this->reset();

        Assertion::greaterOrEqualThan($limit, 0);

        $this->commands[] = new PickUpBasket($basketId);

        while (count($this->products) < $limit) {
            $this->commands[] = $this->addProduct($basketId);
        }

        $this->applyRemoveProductCommands($basketId, $limit);

        return $this->commands;
    }

    private function applyRemoveProductCommands(UuidInterface $basketId, $limit)
    {
        $expectedCountOfRemovedProducts = (int) $limit * 0.3;
        $removeProductCommandsIdx       = 0;

        $commands = [];

        foreach ($this->commands as $key => $command) {
            if (isset($this->commands[$key - 1]) && $command instanceof AddProductToBasket) {
                $previousCommand = $this->commands[$key - 1];
                if ($previousCommand instanceof AddProductToBasket) {
                    ++$removeProductCommandsIdx;
                    if ($removeProductCommandsIdx <= $expectedCountOfRemovedProducts) {
                        $this->commands[$key] = $this->removeProduct($basketId, $previousCommand->productId);
                        $commands[]           = $this->addProduct($basketId);
                        $commands[]           = $this->addProduct($basketId);
                    }
                }
            }
        }

        $this->commands = array_merge($this->commands, $commands);
    }

    private function randomProductName()
    {
        $names = [
            'Apple',
            'Beer',
            'Blender',
            'Glass',
            'HairDryer',
            'Juice',
            'Mango',
            'Phone',
            'Teapot',
            'Water',
        ];

        return $names[array_rand($names)];
    }

    private function removeProduct(UuidInterface $basketId, UuidInterface $productId)
    {
        return new RemoveProductFromBasket($basketId, $productId);
    }

    private function addProduct(UuidInterface $basketId)
    {
        $productId = Uuid::uuid4();

        $this->products[] = $productId;

        return new AddProductToBasket($basketId, $productId, $this->randomProductName());
    }

    private function reset()
    {
        $this->commands = [];
        $this->products = [];
    }
}
