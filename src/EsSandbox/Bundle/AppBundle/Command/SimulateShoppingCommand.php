<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Model\Basket;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Application\CommandBus\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SimulateShoppingCommand extends ContainerAwareCommand
{
    const DEFAULT_LIMIT_OF_PRODUCTS = 10;

    protected function configure()
    {
        $this
            ->setName('es_sandbox:basket:simulate-shopping')
            ->addArgument('basketId', InputArgument::OPTIONAL, 'Id of basket')
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit of products')
            ->setDescription('Example command for simulate shopping');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit    = $this->limit($input);
        $basketId = $this->basketId($input);

        try {
            $this->handle(
                ShoppingSimulation::simulate($basketId, $limit)->get()
            );

            $this->renderRecordedEvents($output, $basketId);
            $this->renderProjection($output, $basketId);
        } catch (\Exception $e) {
            return $this->handleError($output, $e);
        }
    }

    private function handle(array $commands)
    {
        $commandBus = $this->getContainer()->get('es_sandbox.command_bus');

        foreach ($commands as $command) {
            $commandBus->handle($command);
        }
    }

    private function handleError(OutputInterface $output, \Exception $exception)
    {
        $output->writeln('<error>'.$exception->getMessage().'</error>');

        return 1;
    }

    private function renderRecordedEvents(OutputInterface $output, BasketId $basketId)
    {
        $output->writeln('');
        $output->writeln('Facts about your basket:');

        $events = $this->getContainer()
            ->get('es_sandbox.event_store')
            ->aggregateHistoryFor($basketId);

        $table = new Table($output);

        foreach ($events as $event) {
            if ($event instanceof BasketWasPickedUp) {
                $table->addRow(['Basket with id: ', (string) $event->id(), 'was <info>picked up</info>.']);
            }

            if ($event instanceof ProductWasAddedToBasket) {
                $table->addRow(['Product with id: ', (string) $event->productId(), 'was <info>added</info> to basket.']);
            }

            if ($event instanceof ProductWasRemovedFromBasket) {
                $table->addRow(['Product with id: ', (string) $event->productId(), 'was <comment>removed</comment> from basket.']);
            }
        }

        $table->setStyle('borderless');
        $table->render();
    }

    private function renderProjection(OutputInterface $output, BasketId $basketId)
    {
        $output->writeln('');
        $output->writeln('Your basket now contains:');
        $products = $this->getContainer()
            ->get('es_sandbox.projection.basket')
            ->get($basketId->raw());

        $table = new Table($output);
        $table
            ->setHeaders(['productId', 'name']);

        foreach ($products as $product) {
            $table->addRow([$product->productId, $product->name]);
        }

        $table->render();
    }

    private function limit(InputInterface $input)
    {
        return $input->getArgument('limit') ?: self::DEFAULT_LIMIT_OF_PRODUCTS;
    }

    private function basketId(InputInterface $input)
    {
        return ($input->getArgument('basketId') === null) ? BasketId::generate() : BasketId::fromString($input->getArgument('basketId'));
    }
}
