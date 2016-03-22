<?php

namespace tests\unit\tests\EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Application\CommandBus\Command;
use PhpSpec\ObjectBehavior;
use tests\EsSandbox\Common\Infrastructure\CommandBus\CatchesExceptionMiddleware;
use tests\unit\tests\EsSandbox\Common\Infrastructure\CommandBus\Fixtures\NextCallableSpy;

/**
 * @mixin CatchesExceptionMiddleware
 */
class CatchesExceptionMiddlewareSpec extends ObjectBehavior
{
    /** @var NextCallableSpy */
    private $nextCallable;

    public function let()
    {
        $this->nextCallable = new NextCallableSpy();

        $this->beConstructedWith();
    }

    public function it_calls_next(Command $command)
    {
        $this->handle($command, $this->nextCallable);

        $this->shouldCallNextCallable();
    }

    public function it_does_not_catch_exception_by_default(Command $command)
    {
        $this->nextCallable->throwsException(new \DomainException());

        $this->shouldThrow(\DomainException::class)->during('handle', [$command, $this->nextCallable]);
    }

    public function it_has_no_exception_if_have_not_caught()
    {
        $this->releaseException()->shouldBe(null);
    }

    public function it_catches_exception_if_enabled(Command $command)
    {
        $exception = new \DomainException();

        $this->nextCallable->throwsException($exception);

        $this->enableCatching();
        $this->handle($command, $this->nextCallable);

        $this->releaseException()->shouldBe($exception);
    }

    public function it_does_not_catch_exception_if_disabled(Command $command)
    {
        $this->nextCallable->throwsException(new \DomainException());

        $this->enableCatching();
        $this->handle($command, $this->nextCallable);

        $this->disableCatching();

        $this->shouldThrow(\DomainException::class)->during('handle', [$command, $this->nextCallable]);
    }

    public function it_releases_exception_only_once(Command $command)
    {
        $exception = new \DomainException();

        $this->nextCallable->throwsException($exception);

        $this->enableCatching();
        $this->handle($command, $this->nextCallable);

        $this->releaseException();

        $this->releaseException()->shouldBe(null);
    }

    public function it_fails_if_caught_exception_before_releasing_the_previous_one(Command $command)
    {
        $exception = new \DomainException();

        $this->nextCallable->throwsException($exception);
        $this->enableCatching();

        $this->handle($command, $this->nextCallable);

        $this->shouldThrow(\LogicException::class)->during('handle', [$command, $this->nextCallable]);
    }

    public function it_can_catch_two_exceptions_if_the_previous_one_is_released(Command $command)
    {
        $exception = new \DomainException();

        $this->nextCallable->throwsException($exception);

        $this->enableCatching();

        $this->handle($command, $this->nextCallable);
        $this->releaseException();

        $this->handle($command, $this->nextCallable);
    }

    public function getMatchers()
    {
        return [
            'callNextCallable' => function () {
                return $this->nextCallable->hasBeenCalled();
            },
        ];
    }
}
