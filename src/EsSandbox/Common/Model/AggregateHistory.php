<?php

namespace EsSandbox\Common\Model;

use Ramsey\Uuid\UuidInterface;

interface AggregateHistory
{
    /**
     * @param UuidInterface $id
     *
     * @return Event[]
     */
    public function get(UuidInterface $id);
}
