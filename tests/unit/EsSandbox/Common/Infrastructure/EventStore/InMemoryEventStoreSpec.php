<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Infrastructure\EventStore\InMemoryEventStore;
use EsSandbox\Common\Model\AggregateHistory;
use PhpSpec\ObjectBehavior;
use tests\unit\EsSandbox\Common\Infrastructure\EventStore\Fixtures\FakeEvent1;
use tests\unit\EsSandbox\Common\Infrastructure\EventStore\Fixtures\FakeEvent2;
use tests\unit\EsSandbox\Common\Infrastructure\EventStore\Fixtures\FakeIdentifier;

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
        $id = FakeIdentifier::generate();

        $this->commit(new FakeEvent1($id));
        $this->commit(new FakeEvent2($id));

        $this->aggregateHistoryFor($id)->shouldBeLike(AggregateHistory::of($id, [new FakeEvent1($id), new FakeEvent2($id)]));
    }

    public function it_does_not_get_history_of_another_aggregate()
    {
        $id1 = FakeIdentifier::generate();
        $id2 = FakeIdentifier::generate();

        $this->commit(new FakeEvent1($id1));
        $this->commit(new FakeEvent2($id2));

        $this->aggregateHistoryFor($id1)->shouldBeLike(AggregateHistory::of($id1, [new FakeEvent1($id1)]));
    }

    public function it_can_get_empty_history_when_no_events()
    {
        $id = FakeIdentifier::generate();

        $this->aggregateHistoryFor($id)->shouldBeLike(AggregateHistory::of($id, []));
    }

    public function it_can_be_reset()
    {
        $id = FakeIdentifier::generate();

        $this->commit(new FakeEvent1($id));
        $this->commit(new FakeEvent2($id));

        $this->reset();

        $this->aggregateHistoryFor($id)->shouldBeLike(AggregateHistory::of($id, []));
    }

    public function letGo()
    {
        InMemoryEventStore::instance()->reset();
    }
}
