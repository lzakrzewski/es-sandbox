<?php

namespace EsSandbox\Bundle\AppBundle\Command\Component;

use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Model\AggregateHistory;
use EsSandbox\Common\Model\Event;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableCell;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Output\OutputInterface;

class BasketHistoryRenderer
{
    /**
     * @param OutputInterface  $output
     * @param AggregateHistory $events
     */
    public function render(OutputInterface $output, AggregateHistory $events)
    {
        if ($this->hasNotBeenPickedUp($events)) {
            $output->writeln('Your basket has not been picked up.');

            return;
        }

        if ($this->isEmpty($events)) {
            $output->writeln('Your basket is empty.');

            return;
        }

        $this->renderHistory($output, $events);
    }

    private function hasNotBeenPickedUp(AggregateHistory $events)
    {
        return 0 === $events->count();
    }

    private function isEmpty(AggregateHistory $events)
    {
        return 1 === $events->count() && $events[0] instanceof BasketWasPickedUp;
    }

    private function renderHistory(OutputInterface $output, AggregateHistory $events)
    {
        $output->writeln(sprintf('History of events recorded on your basket with id <comment>%s</comment>:', (string) $events[0]->id()));

        $table = new Table($output);
        $table->setHeaders(['eventType', 'payload']);

        foreach ($events as $key => $event) {
            $table->addRow([$this->renderType($event), $this->renderPayload($event)]);
            if ($key < count($events) - 1) {
                $table->addRow([new TableSeparator(['colspan' => 2])]);
            }
        }

        $table->render();
    }

    private function renderType(Event $event)
    {
        $type = $event->type();

        if ($event instanceof ProductWasAddedToBasket) {
            return '<info>'.$type.'</info>';
        }

        if ($event instanceof ProductWasRemovedFromBasket) {
            return '<comment>'.$type.'</comment>';
        }

        return $type;
    }

    private function renderPayload(Event $event)
    {
        $basketId = $event->id()->toString();
        $content  = 'basketId:  '.$basketId."\n";

        if ($event instanceof ProductWasAddedToBasket) {
            $content .= 'productId: '.$event->productId()->toString()."\n";
            $content .= 'name:      '.$event->name();
        }

        if ($event instanceof ProductWasRemovedFromBasket) {
            $content .= 'productId: '.$event->productId()->toString()."\n";
        }

        return new TableCell($content);
    }
}
