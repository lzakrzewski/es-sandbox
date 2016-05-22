<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use EsSandbox\Basket\Application\Projection\BasketProjection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ProjectionCommand extends ConsoleCommand
{
    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $basketId = Uuid::fromString($input->getArgument('basketId'));

        $this->renderProjection($output, $basketId);
    }

    private function renderProjection(OutputInterface $output, UuidInterface $basketId)
    {
        $basketView = $this->projection()->get($basketId);

        $this->getContainer()
            ->get('es_sandbox.bundle.command.component.basket_projection_renderer')
            ->render($output, $basketView);
    }

    /**
     * @return BasketProjection
     */
    abstract protected function projection();
}
