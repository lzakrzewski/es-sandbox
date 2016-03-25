<?php

namespace tests\unit\EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use PhpSpec\ObjectBehavior;

/**
 * @mixin InMemoryStorage
 */
class InMemoryStorageSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('instance');
    }

    public function it_is_singleton()
    {
        $this->shouldBe(InMemoryStorage::instance());
    }

    public function it_reads()
    {
        $value = new \stdClass();
        $this->put('test', $value);

        $this->read('test')->shouldBe($value);
    }

    public function it_fails_when_try_to_read_not_existing_value()
    {
        $this->shouldThrow(\RuntimeException::class)->during('read', ['test']);
    }

    public function it_has_value()
    {
        $value = new \stdClass();
        $this->put('test', $value);

        $this->has('test')->shouldBe(true);
    }

    public function it_has_not_value()
    {
        $this->has('test')->shouldBe(false);
    }

    public function it_has_null()
    {
        $this->put('test', null);

        $this->has('test')->shouldBe(true);
    }

    public function it_can_be_cleared()
    {
        $value = new \stdClass();
        $this->put('test', $value);
        $this->read('test');

        $this->clear();

        $this->has('test')->shouldBe(false);
    }

    public function letGo()
    {
        $this->clear();
    }
}
