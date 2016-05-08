<?php

namespace tests\fixtures;

use EsSandbox\Common\Model\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FakeEvent implements Event
{
    /** @var UuidInterface */
    private $id;

    /**
     * @param UuidInterface $id
     */
    public function __construct(UuidInterface $id)
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
        return new self(Uuid::fromString(json_decode($contents)->id));
    }
}
