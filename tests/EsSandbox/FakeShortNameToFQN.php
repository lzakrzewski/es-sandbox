<?php

namespace tests\EsSandbox;

use EsSandbox\Common\Infrastructure\EventStore\Mapper\ShortNameToFQN;
use tests\fixtures\FakeEvent;

final class FakeShortNameToFQN implements ShortNameToFQN
{
    /** @var ShortNameToFQN  */
    private $mapper;

    /**
     * @param ShortNameToFQN $mapper
     */
    public function __construct(ShortNameToFQN $mapper)
    {
        $this->mapper = $mapper;
    }

    /** {@inheritdoc} */
    public function get($shortName)
    {
        try {
            return $this->mapper->get($shortName);
        } catch (\InvalidArgumentException $e) {
        }

        if ($shortName == $this->fakeEventShortName()) {
            return FakeEvent::class;
        }

        throw new \InvalidArgumentException(sprintf('There is no fqn for %s short name.', $shortName));
    }

    private function fakeEventShortName()
    {
        $reflection = new \ReflectionClass(FakeEvent::class);

        return $reflection->getShortName();
    }
}
