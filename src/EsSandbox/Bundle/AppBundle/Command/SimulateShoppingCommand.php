<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Common\Application\CommandBus\Command;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
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
            ->addArgument('limit', InputArgument::OPTIONAL, 'Limit of products')
            ->addArgument('basketId', InputArgument::OPTIONAL, 'Id of basket')
            ->setDescription('Shopping simulation');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $limit    = $this->limit($input);
        $basketId = $this->basketId($input);

        try {
            $commands = $this->getContainer()
                ->get('es_sandbox.bundle.command.component.shopping_simpulation')
                ->simulate($basketId, $limit);

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
        $events = $this->getContainer()
            ->get('es_sandbox.event_store')
            ->aggregateHistoryFor($basketId);

        $this
            ->getContainer()
            ->get('es_sandbox.bundle.command.component.basket_events_renderer')
            ->render($output, $events);
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
