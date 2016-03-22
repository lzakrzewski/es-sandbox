<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Infrastructure\CommandBus\LogExceptionMiddleware;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use SimpleBus\Message\Message;

/**
 * @mixin LogExceptionMiddleware
 */
class LogExceptionMiddlewareSpec extends ObjectBehavior
{
    public function let(LoggerInterface $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_does_not_log_exception_if_next_middleware_dont_throw_it(LoggerInterface $logger)
    {
        $this->handle($this->message(), $this->callableDontThrowsException());

        $logger->error(Argument::any())->shouldNotBeCalled();
    }

    public function it_log_exception_if_next_middleware_throw_it(LoggerInterface $logger)
    {
        try {
            $this->handle($this->message(), $this->callableThrowsException());
        } catch (\Exception $e) {
        }

        $logger->error(Argument::any())->shouldBeCalled();
    }

    private function callableThrowsException()
    {
        return function () {
            throw new \Exception();
        };
    }

    private function callableDontThrowsException()
    {
        return function () {
        };
    }

    private function message()
    {
        return new \stdClass();
    }
}
