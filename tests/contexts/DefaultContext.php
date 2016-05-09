<?php

namespace tests\contexts;

use Assert\Assertion;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use EsSandbox\Common\Application\CommandBus\Command;
use EsSandbox\Common\Model\Event;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class DefaultContext implements KernelAwareContext, SnippetAcceptingContext
{
    use KernelDictionary;

    /** @var UuidInterface */
    protected $aggregateId;

    /** @var \Exception */
    private $exception;

    /** @AfterScenario */
    public function afterScenario()
    {
        $this->aggregateId = null;
        $this->exception   = null;
    }

    /**
     * @Transform :productId
     */
    public function productId($productId)
    {
        return Uuid::fromString($productId);
    }

    protected function container()
    {
        return $this->getContainer();
    }

    protected function given(array $events)
    {
        $this->container()->get('es_sandbox.event_store')->commit($events);
    }

    protected function when(Command $command)
    {
        try {
            $this->container()->get('es_sandbox.command_bus')->handle($command);
        } catch (\Exception $e) {
            $this->exception = $e;
        }
    }

    protected function expectException($exception)
    {
        Assertion::eq($exception, get_class($this->exception));
    }

    protected function then($expectedEventClass)
    {
        $history = $this->container()->get('es_sandbox.event_store')->aggregateHistoryFor($this->aggregateId);

        Assertion::notEmpty(array_filter($history->toArray(), function (Event $event) use ($expectedEventClass) {
            return $event instanceof $expectedEventClass;
        }), sprintf('There is no recorded expected event "%s" on aggregate "%s".', $expectedEventClass, $this->aggregateId));
    }
}
