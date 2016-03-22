<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\CommandBus\Fixtures;

use EsSandbox\Common\Model\Event;

final class FakeEvent implements Event
{
    /** {@inheritdoc} */
    public function id()
    {
        return FakeIdentifier::generate();
    }
}
