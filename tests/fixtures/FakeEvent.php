<?php

namespace tests\fixtures;

use EsSandbox\Common\Model\Event;
use EsSandbox\Common\Model\EventWithShortNameAsType;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class FakeEvent implements Event
{
    use EventWithShortNameAsType;

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
    public function data()
    {
        return ['id' => (string) $this->id];
    }

    /** {@inheritdoc} */
    public static function fromData(array $data)
    {
        return new self(Uuid::fromString($data['id']));
    }
}
