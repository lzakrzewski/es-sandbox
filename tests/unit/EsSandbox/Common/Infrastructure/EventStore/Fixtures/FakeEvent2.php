<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\EventStore\Fixtures;

use EsSandbox\Common\Model\Event;

final class FakeEvent2 implements Event
{
    /** @var FakeIdentifier */
    private $identifier;

    /**
     * @param FakeIdentifier $identifier
     */
    public function __construct(FakeIdentifier $identifier)
    {
        $this->identifier = $identifier;
    }

    /**
     * @return FakeIdentifier
     */
    public function id()
    {
        return $this->identifier;
    }
}
