<?php

namespace tests\EsSandbox\Common\Infrastructure\CommandBus;

use SimpleBus\Message\Bus\Middleware\MessageBusMiddleware;

class CatchesExceptionMiddleware implements MessageBusMiddleware
{
    /** @var \Exception|null */
    private $exception;

    private $catching = false;

    /**
     * {@inheritdoc}
     */
    public function handle($message, callable $next)
    {
        try {
            $next($message);
        } catch (\Exception $exception) {
            $this->catchException($exception);
        }
    }

    /**
     * @return \Exception|null
     */
    public function releaseException()
    {
        $exception = $this->exception;

        $this->exception = null;

        return $exception;
    }

    public function enableCatching()
    {
        $this->catching = true;
    }

    public function disableCatching()
    {
        $this->catching = false;
    }

    private function catchException(\Exception $exception)
    {
        $this->rethrowExceptionIfCatchingDisabled($exception);

        if ($this->exception) {
            throw new \LogicException('More than one exception occurred');
        }

        $this->exception = $exception;
    }

    private function rethrowExceptionIfCatchingDisabled(\Exception $exception)
    {
        if ($this->catching) {
            return;
        }

        throw $exception;
    }
}
