<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Infrastructure\EventStore\InMemoryEventStore;
use EsSandbox\Common\Model\AggregateHistory;
use PhpSpec\ObjectBehavior;
use tests\fixtures\FakeEvent;
use tests\fixtures\FakeId;
use tests\unit\EsSandbox\Common\Infrastructure\EventStore\Fixtures\FakeEvent2;

/**
 * @mixin InMemoryEventStore
 */
class InMemoryEventStoreSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('instance');
    }

    public function it_is_singleton()
    {
        $store = InMemoryEventStore::instance();

        $this->shouldBe($store);
    }

    public function it_can_get_history_of_aggregate()
    {
        $id = FakeId::generate();

        $this->commit(new FakeEvent($id));
        $this->commit(new FakeEvent2($id));

        $this->aggregateHistoryFor($id)->shouldBeLike(AggregateHistory::of($id, [new FakeEvent($id), new FakeEvent2($id)]));
    }

    public function it_does_not_get_history_of_another_aggregate()
    {
        $id1 = FakeId::generate();
        $id2 = FakeId::generate();

        $this->commit(new FakeEvent($id1));
        $this->commit(new FakeEvent2($id2));

        $this->aggregateHistoryFor($id1)->shouldBeLike(AggregateHistory::of($id1, [new FakeEvent($id1)]));
    }

    public function it_can_get_empty_history_when_no_events()
    {
        $id = FakeId::generate();

        $this->aggregateHistoryFor($id)->shouldBeLike(AggregateHistory::of($id, []));
    }

    public function it_can_be_reset()
    {
        $id = FakeId::generate();

        $this->commit(new FakeEvent($id));
        $this->commit(new FakeEvent2($id));

        $this->reset();

        $this->aggregateHistoryFor($id)->shouldBeLike(AggregateHistory::of($id, []));
    }

    public function letGo()
    {
        InMemoryEventStore::instance()->reset();
    }
}
