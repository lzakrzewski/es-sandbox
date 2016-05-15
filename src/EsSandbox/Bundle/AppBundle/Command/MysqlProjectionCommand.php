<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use Symfony\Component\Console\Input\InputArgument;

class MysqlProjectionCommand extends ProjectionCommand
{
    /** {@inheritdoc} */
    protected function configure()
    {
        $this
            ->setName('es_sandbox:basket:mysql-projection')
            ->addArgument('basketId', InputArgument::REQUIRED, 'Id of basket')
            ->setDescription('Renders basket projection');
    }

    /** {@inheritdoc} */
    protected function projection()
    {
        return $this->getContainer()->get('es_sandbox.projection.basket.mysql');
    }
}
