<?php

namespace EsSandbox\Common\Model\Identifier;

use Assert\Assertion;
use EsSandbox\Common\Model\Identifier;

abstract class IntegerIdentifier implements Identifier
{
    /**
     * @var int
     */
    private $id;

    /**
     * @param int $id
     */
    protected function __construct($id)
    {
        Assertion::integer($id);

        $this->id = $id;
    }

    /**
     * @param int $id
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
        return new static((int) $string);
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
    public function __toString()
    {
        return (string) $this->id;
    }

    /**
     * @return int
     */
    public function raw()
    {
        return $this->id;
    }
}
