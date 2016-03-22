<?php

namespace tests\unit\EsSandbox\Common\Application\CommandBus;

use EsSandbox\Common\Application\CommandBus\DomainEvents;
use EsSandbox\Common\Model\Event;
use PhpSpec\ObjectBehavior;

/**
 * @mixin DomainEvents
 */
class DomainEventsSpec extends ObjectBehavior
{
    public function it_is_singleton()
    {
        $this->beConstructedThrough('instance');

        $domainEvents2 = DomainEvents::instance();

        $this->shouldBe($domainEvents2);
    }

    public function it_does_not_record_events_by_default(Event $event)
    {
        $this->beConstructedThrough('instance');

        $this->record($event);

        $this->recordedMessages()->shouldBe([]);
    }

    public function it_records_events_if_recording_is_enabled(Event $event1, Event $event2, Event $event3)
    {
        $this->beConstructedThrough('instance');

        $this->enableRecording();
        $this->record($event1);
        $this->record($event2);

        $this->disableRecording();
        $this->record($event3);

        $this->recordedMessages()->shouldBe([$event1, $event2]);
    }

    public function it_erases_events(Event $event1, Event $event2)
    {
        $this->beConstructedThrough('instance');

        $this->enableRecording();

        $this->record($event1);
        $this->record($event2);

        $this->eraseMessages();

        $this->recordedMessages()->shouldBe([]);
    }
}
