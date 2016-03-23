<?php

namespace tests\unit\EsSandbox\Common\Application\CommandBus;

use EsSandbox\Common\Application\CommandBus\RecordedEvents;
use EsSandbox\Common\Model\Event;
use PhpSpec\ObjectBehavior;

/**
 * @mixin RecordedEvents
 */
class RecordedEventsSpec extends ObjectBehavior
{
    public function it_is_singleton()
    {
        $this->beConstructedThrough('instance');

        $domainEvents2 = RecordedEvents::instance();

        $this->shouldBe($domainEvents2);
    }

    public function it_records_events(Event $event1, Event $event2)
    {
        $this->beConstructedThrough('instance');

        $this->record($event1);
        $this->record($event2);

        $this->recordedMessages()->shouldBeLike([$event1, $event2]);
    }

    public function it_erases_events(Event $event1, Event $event2)
    {
        $this->beConstructedThrough('instance');

        $this->record($event1);
        $this->record($event2);

        $this->eraseMessages();

        $this->recordedMessages()->shouldBe([]);
    }

    public function letGo()
    {
        $this->eraseMessages();
    }
}
