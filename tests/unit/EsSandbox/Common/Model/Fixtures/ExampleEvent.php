<?php

namespace tests\unit\EsSandbox\Common\Model\Fixtures;

use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\Identifier;

final class ExampleEvent implements Event
{
    /** @var Identifier */
    private $id;

    /**
     * @param Identifier $id
     */
    public function __construct(Identifier $id)
    {
        $this->id = $id;
    }

    /** {@inheritdoc} */
    public function id()
    {
        return $this->id;
    }
}
