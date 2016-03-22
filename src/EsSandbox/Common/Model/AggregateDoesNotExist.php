<?php

namespace EsSandbox\Common\Model;

abstract class AggregateDoesNotExist extends \DomainException
{
    /**
     * @param Identifier $id
     *
     * @return static
     */
    public static function with(Identifier $id)
    {
        return new static(sprintf('An aggregate with id "%s" does not exist', $id));
    }
}
