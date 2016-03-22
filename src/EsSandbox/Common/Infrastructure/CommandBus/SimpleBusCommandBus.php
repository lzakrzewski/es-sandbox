<?php

namespace EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Application\CommandBus\Command;
use EsSandbox\Common\Application\CommandBus\CommandBus;
use SimpleBus\Message\Bus\MessageBus;

class SimpleBusCommandBus implements CommandBus
{
    /** @var MessageBus */
    private $simpleBus;

    public function __construct(MessageBus $simpleBus)
    {
        $this->simpleBus = $simpleBus;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Command $command)
    {
        $this->simpleBus->handle($command);
    }
}
