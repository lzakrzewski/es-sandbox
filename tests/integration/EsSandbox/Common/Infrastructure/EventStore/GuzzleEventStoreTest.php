<?php

namespace tests\integration\EsSandbox\Common\Infrastructure\EventStore;

use EsSandbox\Common\Infrastructure\EventStore\GuzzleEventStore;
use Ramsey\Uuid\Uuid;
use tests\fixtures\FakeEvent;
use tests\integration\IntegrationTestCase;

class GuzzleEventStoreTest extends IntegrationTestCase
{
    /** @var GuzzleEventStore */
    private $eventStore;

    /** @test */
    public function it_can_get_history_of_aggregate()
    {
        $id = Uuid::uuid4();

        $this->eventStore->commit([new FakeEvent($id), new FakeEvent($id)]);

        $this->assertEquals([new FakeEvent($id), new FakeEvent($id)], $this->eventStore->aggregateHistoryFor($id));
    }

    /** @test */
    public function it_does_not_get_history_of_another_aggregate()
    {
        $id1 = Uuid::uuid4();
        $id2 = Uuid::uuid4();

        $this->eventStore->commit([new FakeEvent($id1), new FakeEvent($id2)]);

        $this->assertEquals([new FakeEvent($id1)], $this->eventStore->aggregateHistoryFor($id1));
    }

    /** @test */
    public function it_can_get_empty_history_when_no_events()
    {
        $this->assertEmpty($this->eventStore->aggregateHistoryFor(Uuid::uuid4()));
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->eventStore = $this->container()->get('es_sandbox.event_store.guzzle_eventstore');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->eventStore = null;

        parent::tearDown();
    }
}
