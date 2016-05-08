<?php

namespace tests\unit\EsSandbox\Common\Model;

use EsSandbox\Common\Model\AggregateHistory;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use tests\fixtures\FakeEvent;

/**
 * @mixin AggregateHistory
 */
class AggregateHistorySpec extends ObjectBehavior
{
    public function it_can_be_count()
    {
        $this->beConstructedThrough(
            'of',
            [
                [new FakeEvent(Uuid::uuid4()), new FakeEvent(Uuid::uuid4())],
            ]
        );

        $this->count()->shouldReturn(2);
    }

    public function it_can_be_array()
    {
        $events = [new FakeEvent(Uuid::uuid4()), new FakeEvent(Uuid::uuid4())];

        $this->beConstructedThrough('of', [$events]);

        $this[0]->shouldBeLike($events[0]);
        $this[1]->shouldBeLike($events[1]);
    }

    public function it_is_immutable()
    {
        $this->beConstructedThrough('of', [[new FakeEvent(Uuid::uuid4()), new FakeEvent(Uuid::uuid4())]]);

        $this->shouldThrow(\RuntimeException::class)->during('offsetSet', [new FakeEvent(Uuid::uuid4()), 2]);
    }

    /** @SuppressWarnings(PHPMD.UnusedLocalVariable) */
    public function it_can_be_iterable()
    {
        $events = [new FakeEvent(Uuid::uuid4())];

        $this->beConstructedThrough('of', [$events]);

        foreach ($this as $event) {
            $this->current()->shouldBeLike($events[0]);
        }
    }
}
