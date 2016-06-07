<?php

namespace EsSandbox\Bundle\AppBundle\Command\Component;

use EsSandbox\Basket\Application\Projection\BasketView;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class BasketProjectionRenderer
{
    /**
     * @param OutputInterface $output
     * @param BasketView|null $basketView
     */
    public function render(OutputInterface $output, BasketView $basketView = null)
    {
        if (null === $basketView) {
            $output->writeln('Your basket has not been picked up.');

            return;
        }

        if (empty($basketView->products)) {
            $output->writeln('Your basket is empty.');

            return;
        }

        $output->writeln(sprintf('Your basket with id <comment>%s</comment> contains:', $basketView->basketId));

        $table = new Table($output);
        $table
            ->setHeaders(['productId', 'name']);

        foreach ($basketView->products as $product) {
            $table->addRow([$product->productId, $product->name]);
        }

        $table->render();
    }
}
