<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use Assert\Assertion;
use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class ShoppingSimulation
{
    /** @var UuidInterface */
    private $basketId;

    /** @var Command[] */
    private $commands;

    /** @var UuidInterface */
    private $products = [];

    /** @var int */
    private $limit;

    private function __construct(UuidInterface $basketId, $limit)
    {
        $this->basketId = $basketId;
        $this->limit    = $limit;
    }

    /**
     * @param UuidInterface $basketId
     * @param $limit
     *
     * @return ShoppingSimulation
     */
    public static function simulate(UuidInterface $basketId, $limit)
    {
        $self = new self($basketId, $limit);

        return $self;
    }

    /**
     * @return Command[]
     */
    public function randomCommands()
    {
        return $this->shopping($this->basketId, $this->limit);
    }

    private function shopping(UuidInterface $basketId, $limit)
    {
        Assertion::greaterOrEqualThan($limit, 0);

        $this->commands[] = new PickUpBasket($basketId);

        while (count($this->products) < $limit) {
            $this->commands[] = $this->randomCommand($basketId);
        }

        return $this->commands;
    }

    private function randomCommand(UuidInterface $basketId)
    {
        if (!(bool) rand(0, 3) && count($this->products) > 1) {
            return $this->removeProduct($basketId);
        }

        return $this->addProduct($basketId);
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

    private function removeProduct(UuidInterface $basketId)
    {
        $productToRemove = array_rand($this->products);
        $command         = new RemoveProductFromBasket($basketId, $this->products[$productToRemove]);
        unset($this->products[$productToRemove]);

        return $command;
    }

    private function addProduct(UuidInterface $basketId)
    {
        $productId = Uuid::uuid4();

        $this->products[] = $productId;

        return new AddProductToBasket($basketId, $productId, $this->randomProductName());
    }
}
