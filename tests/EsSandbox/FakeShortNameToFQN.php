<?php

namespace tests\EsSandbox;

use EsSandbox\Common\Infrastructure\EventStore\Mapper\ShortNameToFQN;
use tests\fixtures\FakeEvent;

final class FakeShortNameToFQN implements ShortNameToFQN
{
    /** {@inheritdoc} */
    public function get($shortName)
    {
        return FakeEvent::class;
    }
}
