<?php

namespace EsSandbox\Common\Model;

abstract class Enum
{
    protected $value;

    /**
     * Store existing constants in a static cache per object.
     *
     * @var array
     */
    private static $cache = [];

    public function __construct($value)
    {
        if (!$this->isValid($value)) {
            throw new \UnexpectedValueException(sprintf('Given value "%s" is invalid', $value));
        }

        $this->value = $value;
    }

    /**
     * Static factory (ex. ExampleEnum::ONE()).
     *
     * @param $method
     * @param $arguments
     *
     * @throws \BadMethodCallException When given constant is undefined
     *
     * @return Enum
     */
    public static function __callStatic($method, $arguments)
    {
        if (defined("static::$method")) {
            return new static(constant("static::$method"));
        }

        throw new \BadMethodCallException(sprintf('Undefined class constant "%s" in "%s"', $method, get_called_class()));
    }

    /**
     * @return mixed
     */
    final public function get()
    {
        return $this->value;
    }

    /**
     * @return string
     */
    final public function __toString()
    {
        return (string) $this->value;
    }

    /**
     * Checks if value in enum is equal to $enum.
     *
     * @param Enum $enum
     *
     * @return bool
     */
    final public function equals(Enum $enum)
    {
        return $enum->get() === $this->get();
    }

    protected static function isValid($value)
    {
        $class = get_called_class();

        if (!array_key_exists($class, self::$cache)) {
            $reflection          = new \ReflectionClass($class);
            self::$cache[$class] = $reflection->getConstants();
        }

        return in_array($value, self::$cache[$class], true);
    }
}
