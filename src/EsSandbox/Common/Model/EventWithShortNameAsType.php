<?php

namespace EsSandbox\Common\Model;

trait EventWithShortNameAsType
{
    /**
     * @return string
     */
    public function type()
    {
        $reflection = new \ReflectionClass($this);

        return $reflection->getShortName();
    }
}
