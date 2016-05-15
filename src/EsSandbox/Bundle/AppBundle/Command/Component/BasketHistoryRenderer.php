<?php

namespace EsSandbox\Bundle\AppBundle\Command\Component;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Model\AggregateHistory;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Output\OutputInterface;

class BasketHistoryRenderer
{
    /**
     * @param OutputInterface  $output
     * @param AggregateHistory $events
     */
    public function render(OutputInterface $output, AggregateHistory $events)
    {
        $output->writeln('Facts about your basket:');

        $table = new Table($output);

        if ($this->hasNotBeenPickedUp($events)) {
            $output->writeln('Your basket has not been picked up.');

            return;
        }

        if ($this->isEmpty($events)) {
            $output->writeln('Your basket is empty.');

            return;
        }

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

    private function hasNotBeenPickedUp(AggregateHistory $events)
    {
        return 0 === $events->count();
    }

    private function isEmpty(AggregateHistory $events)
    {
        return 1 === $events->count() && $events[0] instanceof BasketWasPickedUp;
    }
}
