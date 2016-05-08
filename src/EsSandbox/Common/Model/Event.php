<?php

namespace EsSandbox\Common\Model;

//Todo: remove toString, add toArray
use Ramsey\Uuid\UuidInterface;

interface Event
{
    /**
     * @return UuidInterface
     */
    public function id();

    /**
     * @return Event
     */
    public function __toString();

    /**
     * @param $contents
     *
     * @return Event
     */
    public static function fromString($contents);
}
