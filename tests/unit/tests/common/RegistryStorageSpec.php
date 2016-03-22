<?php

namespace tests\unit\tests\common;

use PhpSpec\ObjectBehavior;
use tests\common\RegistryStorage;

class RegistryStorageSpec extends ObjectBehavior
{
    public function let()
    {
        $this->beConstructedThrough('instance');
        $this->clear();
    }

    public function it_is_singleton()
    {
        $this->shouldBe(RegistryStorage::instance());
    }

    public function it_shares(\stdClass $value)
    {
        $this->share('test', $value);

        $this->shared('test')->shouldBe($value);
    }

    public function it_fails_when_not_shared()
    {
        $this->shouldThrow(\LogicException::class)->duringShared('test');
    }

    public function it_has_shared_value(\stdClass $value)
    {
        $this->share('test', $value);

        $this->hasShared('test')->shouldBe(true);
    }

    public function it_has_not_shared_value_by_default()
    {
        $this->hasShared('test')->shouldBe(false);
    }

    public function it_can_be_cleared(\stdClass $value)
    {
        $this->share('test', $value);
        $this->clear();

        $this->shouldThrow(\LogicException::class)->duringShared('test');
    }

    public function it_gets_default_when_not_shared()
    {
        $this->sharedOrDefault('test', 'default')->shouldBe('default');
    }

    public function it_gets_shared_when_shared(\stdClass $value)
    {
        $this->share('test', $value);

        $this->sharedOrDefault('test', 'default')->shouldBe($value);
    }
}
