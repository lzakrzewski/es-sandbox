<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\CommandBus;

use Assert\Assertion;
use EsSandbox\Common\Application\CommandBus\RecordedEvents;
use EsSandbox\Common\Infrastructure\CommandBus\CommitRecordedEventsMiddleware;
use EsSandbox\Common\Model\EventStore;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use tests\fixtures\FakeEvent;
use tests\fixtures\FakeId;

/**
 * @mixin CommitRecordedEventsMiddleware
 */
class CommitRecordedEventsMiddlewareSpec extends ObjectBehavior
{
    public function let(EventStore $eventStore)
    {
        $this->beConstructedWith($eventStore, RecordedEvents::instance());
    }

    public function it_commits_recorded_events(EventStore $eventStore)
    {
        $event = new FakeEvent(FakeId::generate());
        RecordedEvents::instance()->record($event);

        $this->handle($this->message(), $this->dummyCallable());

        $eventStore->commit($event)->shouldBeCalled();
    }

    public function it_does_not_commit_events_when_no_recorded_events(EventStore $eventStore)
    {
        $this->handle($this->message(), $this->dummyCallable());

        $eventStore->commit(Argument::any())->shouldNotBeCalled();
    }

    public function it_does_not_erase_events_after_commit()
    {
        $event = new FakeEvent(FakeId::generate());
        RecordedEvents::instance()->record($event);

        $this->handle($this->message(), $this->dummyCallable());

        Assertion::count(RecordedEvents::instance()->recordedMessages(), 1);
    }

    private function message()
    {
        return new \stdClass();
    }

    private function dummyCallable()
    {
        return function () {
        };
    }

    public function letGo()
    {
        RecordedEvents::instance()->eraseMessages();
    }
}
