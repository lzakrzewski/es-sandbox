<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderBasketProjectionCommand extends ConsoleCommand
{
    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('es_sandbox:basket:render-basket-projection')
            ->addArgument('basketId', InputArgument::OPTIONAL, 'Id of basket')
            ->addArgument('engine', InputArgument::OPTIONAL, 'Engine of projection (event-store/mysql).')
            ->setDescription('Renders basket projection');
    }

    /** {@inheritdoc} */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $basketId = $this->basketId($input);

            $this->renderProjection($output, $basketId);
        } catch (\Exception $e) {
            return $this->handleError($output, $e);
        }
    }

    private function renderProjection(OutputInterface $output, UuidInterface $basketId)
    {
        $basketView = $this->projection()->get($basketId);

        $this->getContainer()
            ->get('es_sandbox.bundle.command.component.basket_projection_renderer')
            ->render($output, $basketView);
    }

    private function projection()
    {
        return $this->getContainer()->get('es_sandbox.projection.basket.event_store');
    }

    private function basketId(InputInterface $input)
    {
        return (null !== $input->getArgument('basketId')) ? Uuid::fromString($input->getArgument('basketId')) : $this->lastBasketId();
    }

    private function lastBasketId()
    {
        return $this->getContainer()->get('es_sandbox.projection.last_basket_id.mysql')->get();
    }
}
