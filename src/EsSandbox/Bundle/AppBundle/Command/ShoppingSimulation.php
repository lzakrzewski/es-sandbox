<?php

namespace EsSandbox\Bundle\AppBundle\Command;

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
    /** @var UuidInterface */
    private $basketId;

    /** @var Command[] */
    private $commands;

    /** @var UuidInterface[] */
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
}
