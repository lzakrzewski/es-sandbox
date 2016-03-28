<?php

namespace tests\unit\EsSandbox\Common\Model;

use EsSandbox\Common\Model\AggregateHistory;
use PhpSpec\ObjectBehavior;
use tests\fixtures\FakeEvent;
use tests\fixtures\FakeId;

/**
 * @mixin AggregateHistory
 */
class AggregateHistorySpec extends ObjectBehavior
{
    public function it_can_be_count()
    {
        $id = FakeId::generate();

        $this->beConstructedThrough('of', [$id, [new FakeEvent($id), new FakeEvent($id)]]);

        $this->count()->shouldReturn(2);
    }

    public function it_can_be_array()
    {
        $id     = FakeId::generate();
        $events = [new FakeEvent($id), new FakeEvent($id)];

        $this->beConstructedThrough('of', [$id, $events]);

        $this[0]->shouldBeLike($events[0]);
        $this[1]->shouldBeLike($events[1]);
    }

    public function it_is_immutable()
    {
        $id = FakeId::generate();

        $this->beConstructedThrough('of', [$id, [new FakeEvent($id), new FakeEvent($id)]]);

        $this->shouldThrow(\RuntimeException::class)->during('offsetSet', [new FakeEvent($id), 2]);
    }

    /** @SuppressWarnings(PHPMD.UnusedLocalVariable) */
    public function it_can_be_iterable()
    {
        $id     = FakeId::generate();
        $events = [new FakeEvent($id)];

        $this->beConstructedThrough('of', [$id, $events]);

        foreach ($this as $event) {
            $this->current()->shouldBeLike($events[0]);
        }
    }
}
