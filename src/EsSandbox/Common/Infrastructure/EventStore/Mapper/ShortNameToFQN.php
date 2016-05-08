<?php

namespace EsSandbox\Common\Infrastructure\EventStore\Mapper;

interface ShortNameToFQN
{
    /**
     * @param $shortName
     *
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    public function get($shortName);
}
