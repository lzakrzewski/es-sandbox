<?php

namespace EsSandbox\Common\Application\CommandBus;

use Ramsey\Uuid\UuidInterface;

interface Command
{
    /**
     * @return UuidInterface
     */
    public function id();
}
