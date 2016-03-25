<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use Assert\Assertion;
use EsSandbox\Basket\Application\Command\AddProductToBasket;
use EsSandbox\Basket\Application\Command\PickUpBasket;
use EsSandbox\Basket\Application\Command\RemoveProductFromBasket;
use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * Todo: refactor this shitty code
 */
class SimulateShoppingCommand extends ContainerAwareCommand
{
    const DEFAULT_LIMIT_OF_PRODUCTS = 10;

    protected function configure()
    {
        $this
            ->setName('es_sandbox:basket:simulate-shopping')
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit of products')
            ->setDescription('Example command for simulate shopping');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit = $input->getArgument('limit') ?: self::DEFAULT_LIMIT_OF_PRODUCTS;

        try {
            $this->shopping($output, $limit);
        } catch (\Exception $e) {
            return $this->handleError($output, $e);
        }
    }

    private function shopping(OutputInterface $output, $limit)
    {
        $basketId   = BasketId::generate();
        $commandBus = $this->getContainer()->get('es_sandbox.command_bus');

        Assertion::greaterOrEqualThan($limit, 0);

        $commandBus->handle(new PickUpBasket($basketId->raw()));
        $output->writeln('===============================================================================');
        $output->writeln(PHP_EOL.sprintf('Basket with id %s was <info>picked up</info>.', $basketId).PHP_EOL);

        $count = 0;

        while ($count < $limit) {
            $command = $this->handleCommandOnBasket($basketId);

            $this->printCommandInfo($output, $command);

            $count = $this->basket($basketId)->count();
        }

        $output->writeln('===============================================================================');
        $output->writeln('Your basket contains products:');

        print_r($this->getContainer()->get('es_sandbox.projection.basket')->get($basketId->raw()));
    }

    private function handleError(OutputInterface $output, \Exception $exception)
    {
        $output->writeln('<error>'.$exception->getMessage().'</error>');

        return 1;
    }

    private function handleCommandOnBasket(BasketId $basketId)
    {
        $commandBus = $this->getContainer()->get('es_sandbox.command_bus');
        $basket     = $this->basket($basketId);

        if ($basket->count() > 0) {
            $commandBus->handle($command = $this->randomCommand($basket));

            return $command;
        }

        $commandBus->handle($command = new AddProductToBasket($basketId->raw(), ProductId::generate()->raw(), $this->randomProductName()));

        return $command;
    }

    private function printCommandInfo(OutputInterface $output, Command $command)
    {
        if ($command instanceof RemoveProductFromBasket) {
            $output->writeln(sprintf('Product with id %s was <comment>removed</comment> from basket.', $command->productId));

            return;
        }

        $output->writeln(sprintf('Product with id %s was <info>added</info> to basket.', $command->productId));
    }

    private function basket(BasketId $basketId)
    {
        $events = $this->getContainer()->get('es_sandbox.event_store')->aggregateHistoryFor($basketId);

        return Basket::reconstituteFrom($events);
    }

    private function randomCommand(Basket $basket)
    {
        $productId = ProductId::generate();

        if ((bool) rand(0, 3)) {
            return new AddProductToBasket($basket->id()->raw(), $productId->raw(), $this->randomProductName());
        }

        $products        = $basket->products();
        $productToRemove = array_rand(array_keys($basket->products()));

        return new RemoveProductFromBasket($basket->id()->raw(), Uuid::fromString(array_keys($products)[$productToRemove]));
    }

    private function randomProductName()
    {
        return array_rand([
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
        ]);
    }
}
