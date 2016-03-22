<?php

namespace EsSandbox\Common\Application\CommandBus;

use Ramsey\Uuid\UuidInterface;

interface Command
{
    /**
     * @return string|int|UuidInterface
     */
    public function id();
}
