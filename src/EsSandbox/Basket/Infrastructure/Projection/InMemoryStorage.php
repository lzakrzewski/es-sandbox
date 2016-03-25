<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

final class InMemoryStorage
{
    /** @var self */
    private static $instance;

    /** @var array */
    private $registry = [];

    /**
     * @return InMemoryStorage
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /** {@inheritdoc} */
    public function put($key, $value)
    {
        $this->registry[$key] = $value;
    }

    /** {@inheritdoc} */
    public function read($key)
    {
        if (!$this->has($key)) {
            throw new \RuntimeException(sprintf('There is no object "%s" in memory', $key));
        }

        return $this->registry[$key];
    }

    /** {@inheritdoc} */
    public function has($key)
    {
        return array_key_exists($key, $this->registry);
    }

    public function clear()
    {
        $this->registry = [];
    }

    private function __construct()
    {
    }
}
