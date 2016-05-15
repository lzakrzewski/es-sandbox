<?php

namespace EsSandbox\Bundle\AppBundle\Command\Component;

use Assert\Assertion;
use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
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

        $this->applyRemoveProductCommands($basketId);

        return $this->commands;
    }

    private function applyRemoveProductCommands(UuidInterface $basketId)
    {
        foreach ($this->commands as $key => $command) {
            if ($command instanceof ProductWasAddedToBasket && $previousCommand = $this->commands[$key - 1] instanceof ProductWasAddedToBasket) {
                if (!(bool) rand(0, 3)) {
                    $this->commands[$key] = $this->removeProduct($basketId, $previousCommand->productId);
                }
            }
        }
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
