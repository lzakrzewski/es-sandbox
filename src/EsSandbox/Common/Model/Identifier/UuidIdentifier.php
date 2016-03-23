<?php

namespace EsSandbox\Common\Model\Identifier;

use EsSandbox\Common\Model\Identifier;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

abstract class UuidIdentifier implements Identifier
{
    /**
     * @var UuidInterface
     */
    private $uuid;

    /**
     * @param UuidInterface $uuid
     */
    protected function __construct(UuidInterface $uuid)
    {
        $this->uuid = $uuid;
    }

    /**
     * @return static
     */
    public static function generate()
    {
        return new static(Uuid::uuid4());
    }

    /**
     * @param UuidInterface $uuid
     *
     * @return static
     */
    public static function of(UuidInterface $uuid)
    {
        return new static($uuid);
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public static function fromString($string)
    {
        return new static(Uuid::fromString($string));
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Identifier $identifier)
    {
        if (!$identifier instanceof static) {
            return false;
        }

        return $this->uuid->equals($identifier->uuid);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->uuid->toString();
    }

    /**
     * @return UuidInterface
     */
    public function raw()
    {
        return $this->uuid;
    }
}
