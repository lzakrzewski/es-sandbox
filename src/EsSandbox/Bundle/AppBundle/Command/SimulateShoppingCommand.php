<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class SimulateShoppingCommand extends ConsoleCommand
{
    const DEFAULT_LIMIT_OF_PRODUCTS = 10;

    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('es_sandbox:basket:simulate-shopping')
            ->addArgument('basketId', InputArgument::OPTIONAL, 'Id of basket')
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit of products')
            ->setDescription('Shopping simulation');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit    = $this->limit($input);
        $basketId = $this->basketId($input);

        try {
            $commands = ShoppingSimulation::simulate($basketId, $limit)
                ->randomCommands();

            foreach ($commands as $command) {
                $this->handleCommand($command);
            }

            $this->renderRecordedEvents($output, $basketId);
        } catch (\Exception $e) {
            return $this->handleError($output, $e);
        }
    }

    private function handleCommand(Command $command)
    {
        $commandBus = $this->getContainer()->get('es_sandbox.command_bus');
        $commandBus->handle($command);
    }

    private function renderRecordedEvents(OutputInterface $output, UuidInterface $basketId)
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

    private function limit(InputInterface $input)
    {
        return $input->getArgument('limit') ?: self::DEFAULT_LIMIT_OF_PRODUCTS;
    }

    private function basketId(InputInterface $input)
    {
        return ($input->getArgument('basketId') === null) ? Uuid::uuid4() : Uuid::fromString($input->getArgument('basketId'));
    }
}
