<?php

namespace EsSandbox\Common\Model;

interface Event
{
    /**
     * @return Identifier
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
    public function fromString($contents);
}
