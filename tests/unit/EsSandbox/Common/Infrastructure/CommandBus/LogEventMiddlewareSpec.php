<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\CommandBus;

use Assert\AssertionFailedException;
use EsSandbox\Common\Infrastructure\CommandBus\LogEventMiddleware;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Psr\Log\LoggerInterface;
use Ramsey\Uuid\Uuid;
use tests\fixtures\FakeEvent;

/**
 * @mixin LogEventMiddleware
 */
class LogEventMiddlewareSpec extends ObjectBehavior
{
    public function let(LoggerInterface $logger)
    {
        $this->beConstructedWith($logger);
    }

    public function it_logs_message_when_it_is_event(LoggerInterface $logger)
    {
        $event = new FakeEvent(Uuid::uuid4());
        $logger->info(Argument::cetera())->shouldBeCalled();

        $this->handle($event, $this->dummyCallable());
    }

    public function it_throws_exception_when_it_is_not_event(LoggerInterface $logger)
    {
        $message = new \stdClass();
        $logger->info(Argument::cetera())->shouldNotBeCalled();

        $this->shouldThrow(AssertionFailedException::class)->during('handle', [$message, $this->dummyCallable()]);
    }

    private function dummyCallable()
    {
        return function () {
        };
    }
}
