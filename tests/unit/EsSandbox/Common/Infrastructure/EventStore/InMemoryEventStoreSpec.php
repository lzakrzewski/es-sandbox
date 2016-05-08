<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Infrastructure\EventStore\InMemoryEventStore;
use EsSandbox\Common\Model\AggregateHistory;
use PhpSpec\ObjectBehavior;
use Ramsey\Uuid\Uuid;
use tests\fixtures\FakeEvent;

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
        $id = Uuid::uuid4();

        $this->commit(new FakeEvent($id));
        $this->commit(new FakeEvent($id));

        $this->aggregateHistoryFor($id)
            ->shouldBeLike(AggregateHistory::of([new FakeEvent($id), new FakeEvent($id)]));
    }

    public function it_does_not_get_history_of_another_aggregate()
    {
        $id1 = Uuid::uuid4();
        $id2 = Uuid::uuid4();

        $this->commit(new FakeEvent($id1));
        $this->commit(new FakeEvent($id2));

        $this->aggregateHistoryFor($id1)
            ->shouldBeLike(AggregateHistory::of([new FakeEvent($id1)]));
    }

    public function it_can_get_empty_history_when_no_events()
    {
        $id = Uuid::uuid4();

        $this->aggregateHistoryFor($id)
            ->shouldBeLike(AggregateHistory::of([]));
    }

    public function it_can_be_reset()
    {
        $id = Uuid::uuid4();

        $this->commit(new FakeEvent($id));
        $this->commit(new FakeEvent($id));

        $this->reset();

        $this->aggregateHistoryFor($id)
            ->shouldBeLike(AggregateHistory::of([]));
    }

    public function letGo()
    {
        InMemoryEventStore::instance()->reset();
    }
}
