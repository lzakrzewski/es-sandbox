<?php

namespace tests\unit\EsSandbox\Common\Model;

use EsSandbox\Common\Model\AggregateHistory;
use PhpSpec\ObjectBehavior;
use tests\unit\EsSandbox\Common\Model\Fixtures\ExampleEvent;
use tests\unit\EsSandbox\Common\Model\Identifier\Fixtures\TestUuid1;

/**
 * @mixin AggregateHistory
 */
class AggregateHistorySpec extends ObjectBehavior
{
    public function it_can_be_count()
    {
        $id = TestUuid1::generate();

        $this->beConstructedThrough('of', [$id, [new ExampleEvent($id), new ExampleEvent($id)]]);

        $this->count()->shouldReturn(2);
    }

    public function it_can_be_array()
    {
        $id     = TestUuid1::generate();
        $events = [new ExampleEvent($id), new ExampleEvent($id)];

        $this->beConstructedThrough('of', [$id, $events]);

        $this[0]->shouldBeLike($events[0]);
        $this[1]->shouldBeLike($events[1]);
    }

    public function it_is_immutable()
    {
        $id = TestUuid1::generate();

        $this->beConstructedThrough('of', [$id, [new ExampleEvent($id), new ExampleEvent($id)]]);

        $this->shouldThrow(\RuntimeException::class)->during('offsetSet', [new ExampleEvent($id), 2]);
    }

    /** @SuppressWarnings(PHPMD.UnusedLocalVariable) */
    public function it_can_be_iterable()
    {
        $id     = TestUuid1::generate();
        $events = [new ExampleEvent($id)];

        $this->beConstructedThrough('of', [$id, $events]);

        foreach ($this as $event) {
            $this->current()->shouldBeLike($events[0]);
        }
    }
}
