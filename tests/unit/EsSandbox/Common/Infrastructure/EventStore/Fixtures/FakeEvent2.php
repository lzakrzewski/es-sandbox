<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\EventStore\Fixtures;

use EsSandbox\Common\Model\Event;
use tests\fixtures\FakeId;

final class FakeEvent2 implements Event
{
    /** @var FakeId */
    private $id;

    /**
     * @param FakeId $id
     */
    public function __construct(FakeId $id)
    {
        $this->id = $id;
    }

    /** {@inheritdoc} */
    public function id()
    {
        return $this->id;
    }

    /** {@inheritdoc} */
    public function __toString()
    {
        return (string) json_encode(['id' => (string) $this->id]);
    }

    /** {@inheritdoc} */
    public static function fromString($contents)
    {
        return new self(FakeId::fromString(json_decode($contents)->id));
    }
}
