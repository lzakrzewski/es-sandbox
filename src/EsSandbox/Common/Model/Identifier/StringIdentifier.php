<?php

namespace EsSandbox\Common\Model\Identifier;

use Assert\Assertion;
use EsSandbox\Common\Model\Identifier;

abstract class StringIdentifier implements Identifier
{
    /** @var string */
    private $id;

    /**
     * @param string $id
     */
    protected function __construct($id)
    {
        Assertion::string($id);
        Assertion::notEmpty($id);

        $this->id = $id;
    }

    /**
     * @param string $id
     *
     * @return static
     */
    public static function of($id)
    {
        return new static($id);
    }

    /**
     * {@inheritdoc}
     *
     * @return static
     */
    public static function fromString($string)
    {
        return new static($string);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function equals(Identifier $identifier)
    {
        return $this == $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function raw()
    {
        return $this->__toString();
    }
}
