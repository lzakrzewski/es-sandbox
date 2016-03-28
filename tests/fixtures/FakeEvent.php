<?php

namespace tests\fixtures;

use EsSandbox\Common\Model\Event;

final class FakeEvent implements Event
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
    public function fromString($contents)
    {
        return new self(FakeId::fromString(json_decode($contents)->id));
    }
}
