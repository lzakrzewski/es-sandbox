<?php

namespace tests\unit\tests\EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Model\Event;
use PhpSpec\ObjectBehavior;
use tests\EsSandbox\Common\Infrastructure\CommandBus\CollectsEventsMiddleware;
use tests\unit\tests\EsSandbox\Common\Infrastructure\CommandBus\Fixtures\NextCallableSpy;

/**
 * @mixin CollectsEventsMiddleware
 */
class CollectsEventsMiddlewareSpec extends ObjectBehavior
{
    /** @var NextCallableSpy */
    private $nextCallable;

    public function let()
    {
        $this->nextCallable = new NextCallableSpy();

        $this->beConstructedWith();
    }

    public function it_calls_next(Event $event)
    {
        $this->handle($event, $this->nextCallable);

        $this->shouldCallNextCallable();
    }

    public function it_has_empty_events_if_not_collected()
    {
        $this->events()->shouldBe([]);
    }

    public function it_collects_events(Event $event1, Event $event2)
    {
        $this->handle($event1, $this->nextCallable);
        $this->handle($event2, $this->nextCallable);

        $this->events()->shouldBe([$event1, $event2]);
    }

    public function it_handles_only_domain_events()
    {
        $messageNotEvent = new \stdClass();

        $this->shouldThrow(\LogicException::class)->during('handle', [$messageNotEvent, $this->nextCallable]);
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
