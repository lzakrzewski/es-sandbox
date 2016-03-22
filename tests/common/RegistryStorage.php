<?php

namespace tests\common;

final class RegistryStorage
{
    /** @var self */
    private static $instance;

    /** @var array */
    private $registry = [];

    /**
     * @return RegistryStorage
     */
    public static function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function share($key, $value)
    {
        $this->registry[$key] = $value;
    }

    public function shared($key)
    {
        if (!$this->hasShared($key)) {
            throw new \LogicException(sprintf('There is no shared object "%s"', $key));
        }

        return $this->registry[$key];
    }

    public function clear()
    {
        $this->registry = [];
    }

    private function __construct()
    {
    }

    public function hasShared($key)
    {
        return isset($this->registry[$key]);
    }

    public function sharedOrDefault($key, $default)
    {
        try {
            return $this->shared($key);
        } catch (\LogicException $e) {
            return $default;
        }
    }
}
