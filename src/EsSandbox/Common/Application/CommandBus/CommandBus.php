<?php

namespace EsSandbox\Common\Application\CommandBus;

interface CommandBus
{
    /**
     * @param Command $command
     */
    public function handle(Command $command);
}
