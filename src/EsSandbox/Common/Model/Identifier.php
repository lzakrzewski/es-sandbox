<?php

namespace EsSandbox\Common\Model;

use Ramsey\Uuid\UuidInterface;

interface Identifier
{
    /**
     * Creates an identifier object from a string representation.
     *
     * @param $string
     *
     * @return Identifier
     */
    public static function fromString($string);

    /**
     * Returns a string that can be parsed by fromString().
     *
     * @return string
     */
    public function __toString();

    /**
     * Compares the object to another Identifier object. Returns true if both have the same type and value.
     *
     * @param Identifier $identifier
     *
     * @return bool
     */
    public function equals(Identifier $identifier);

    /**
     * Returns a raw value of identifier.
     *
     * @return UuidInterface|int|string
     */
    public function raw();
}
