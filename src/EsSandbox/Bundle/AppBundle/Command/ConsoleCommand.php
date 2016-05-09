<?php

namespace EsSandbox\Bundle\AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Output\OutputInterface;

abstract class ConsoleCommand extends ContainerAwareCommand
{
    /**
     * @param OutputInterface $output
     * @param \Exception      $exception
     *
     * @return int
     */
    protected function handleError(OutputInterface $output, \Exception $exception)
    {
        $output->writeln('<error>'.$exception->getMessage().'</error>');

        return 1;
    }
}
