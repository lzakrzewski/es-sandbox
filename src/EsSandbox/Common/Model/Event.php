<?php

namespace EsSandbox\Common\Model;

use Ramsey\Uuid\UuidInterface;

interface Event
{
    /**
     * @return UuidInterface
     */
    public function id();

    /**
     * @return array
     */
    public function toArray();

    /**
     * @param array $contents
     *
     * @return Event
     */
    public static function fromArray(array $contents);
}
