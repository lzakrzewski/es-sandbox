<?php

namespace EsSandbox\Common\Model;

use Ramsey\Uuid\UuidInterface;

interface Event
{
    /**
     * @return UuidInterface
     */
    public function id();
}
