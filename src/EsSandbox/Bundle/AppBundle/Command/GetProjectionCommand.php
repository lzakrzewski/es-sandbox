<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//Todo: rename to EventStoreProjectionCommand, add MysqlProjectionCommand
//Extract component, add abstraction for projection renderers
class GetProjectionCommand extends ConsoleCommand
{
    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('es_sandbox:basket:get-projection')
            ->addArgument('basketId', InputArgument::REQUIRED, 'Id of basket')
            ->setDescription('Gets basket projection');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $basketId = Uuid::fromString($input->getArgument('basketId'));

        $this->renderProjection($output, $basketId);
    }

    private function renderProjection(OutputInterface $output, UuidInterface $basketId)
    {
        $output->writeln('');
        $output->writeln('Your basket contains:');

        $basketView = $this->getContainer()->get('es_sandbox.projection.basket')->get($basketId);

        if (null === $basketView) {
            return;
        }

        $table = new Table($output);
        $table
            ->setHeaders(['productId', 'name']);

        foreach ($basketView->products as $product) {
            $table->addRow([$product->productId, $product->name]);
        }

        $table->render();
    }
}
