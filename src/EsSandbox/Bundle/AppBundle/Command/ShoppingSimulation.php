<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use Assert\Assertion;
use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Common\Application\CommandBus\Command;

class ShoppingSimulation
{
    /** @var BasketId */
    private $basketId;

    /** @var Command[] */
    private $commands;

    /** @var ProductId[] */
    private $products = [];

    /** @var int */
    private $limit;

    private function __construct(BasketId $basketId, $limit)
    {
        $this->basketId = $basketId;
        $this->limit    = $limit;
    }

    /**
     * @param BasketId $basketId
     * @param $limit
     *
     * @return ShoppingSimulation
     */
    public static function simulate(BasketId $basketId, $limit)
    {
        $self = new self($basketId, $limit);

        return $self;
    }

    /**
     * @return Command[]
     */
    public function get()
    {
        return $this->shopping($this->basketId, $this->limit);
    }

    private function shopping(BasketId $basketId, $limit)
    {
        Assertion::greaterOrEqualThan($limit, 0);

        $this->commands[] = new PickUpBasket($basketId->raw());

        while (count($this->products) < $limit) {
            $this->commands[] = $this->randomCommand($basketId);
        }

        return $this->commands;
    }

    private function randomCommand(BasketId $basketId)
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

    private function removeProduct(BasketId $basketId)
    {
        $productToRemove = array_rand($this->products);
        $command         = new RemoveProductFromBasket($basketId->raw(), $this->products[$productToRemove]->raw());
        unset($this->products[$productToRemove]);

        return $command;
    }

    private function addProduct(BasketId $basketId)
    {
        $productId = ProductId::generate();

        $this->products[] = $productId;

        return new AddProductToBasket($basketId->raw(), $productId->raw(), $this->randomProductName());
    }
}
