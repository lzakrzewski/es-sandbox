<?php

namespace EsSandbox\Common\Model;

use HttpEventStore\WritableToStream;
use Ramsey\Uuid\UuidInterface;

interface Event extends WritableToStream
{
    /**
     * @return UuidInterface
     */
    public function id();

    /**
     * @return string
     */
    public function type();

    /**
     * @return array
     */
    public function data();

    /**
     * @param array $data
     *
     * @return Event
     */
    public static function fromData(array $data);
}
