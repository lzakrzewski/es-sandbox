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
    public function toArray()
    {
        return ['id' => (string) $this->id];
    }

    /** {@inheritdoc} */
    public static function fromArray(array $contents)
    {
        return new self(Uuid::fromString($contents['id']));
    }
}
