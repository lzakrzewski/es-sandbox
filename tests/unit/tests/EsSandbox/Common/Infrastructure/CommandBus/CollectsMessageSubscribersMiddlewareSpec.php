<?php

namespace tests\unit\tests\EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Model\Event;
use PhpSpec\ObjectBehavior;
use SimpleBus\Message\Subscriber\Resolver\MessageSubscribersResolver;
use tests\EsSandbox\Common\Infrastructure\CommandBus\CollectsMessageSubscribersMiddleware;
use tests\unit\tests\EsSandbox\Common\Infrastructure\CommandBus\Fixtures\NextCallableSpy;

/**
 * @mixin CollectsMessageSubscribersMiddleware
 */
class CollectsMessageSubscribersMiddlewareSpec extends ObjectBehavior
{
    /** @var NextCallableSpy */
    private $nextCallable;

    public function let(MessageSubscribersResolver $resolver)
    {
        $this->nextCallable = new NextCallableSpy();

        $this->beConstructedWith($resolver);
    }

    public function it_calls_next(Event $event, MessageSubscribersResolver $resolver)
    {
        $resolver->resolve($event)->willReturn([]);

        $this->handle($event, $this->nextCallable);

        $this->shouldCallNextCallable();
    }

    public function it_has_empty_message_subscribers_if_not_collected()
    {
        $this->messageSubscribers()->shouldBe([]);
    }

    public function it_collects_message_subscribers(Event $event1, Event $event2, MessageSubscribersResolver $resolver)
    {
        $subscriber1 = new \stdClass();
        $subscriber2 = new \stdClass();

        $resolver->resolve($event1)->willReturn([[$subscriber1]]);
        $resolver->resolve($event2)->willReturn([[$subscriber2]]);

        $this->handle($event1, $this->nextCallable);
        $this->handle($event2, $this->nextCallable);

        $this->messageSubscribers()->shouldBe([$subscriber1, $subscriber2]);
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
