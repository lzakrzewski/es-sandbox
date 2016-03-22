<?php

namespace tests\unit\tests\EsSandbox\Common\Infrastructure\CommandBus\Fixtures;

class NextCallableSpy
{
    private $hasBeenCalled = false;

    /** @var \Exception */
    private $throwsException;

    public function __invoke()
    {
        $this->hasBeenCalled = true;

        if ($this->throwsException) {
            throw $this->throwsException;
        }
    }

    public function hasBeenCalled()
    {
        return $this->hasBeenCalled;
    }

    public function throwsException(\Exception $exception)
    {
        return $this->throwsException = $exception;
    }
}
