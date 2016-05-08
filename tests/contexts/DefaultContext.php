<?php

namespace tests\contexts;

use Assert\Assertion;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Common\Application\CommandBus\Command;
use EsSandbox\Common\Model\Event;
use Ramsey\Uuid\Uuid;

class DefaultContext implements KernelAwareContext, SnippetAcceptingContext
{
    use KernelDictionary;

    /** @var \Exception */
    private $exception;

    /** @BeforeScenario */
    public function beforeScenario()
    {
        InMemoryStorage::instance()->clear();
    }

    /** @AfterScenario */
    public function afterScenario()
    {
        $this->exception = null;
    }

    protected function container()
    {
        return $this->getContainer();
    }

    /**
     * @Transform :basketId
     */
    public function basketId($basketId)
    {
        return Uuid::fromString($basketId);
    }

    /**
     * @Transform :productId
     */
    public function productId($productId)
    {
        return Uuid::fromString($productId);
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
        $events = $this->container()->get('es_sandbox.event_store')->events();

        Assertion::notEmpty(array_filter($events, function (Event $event) use ($expectedEventClass) {
            return $event instanceof $expectedEventClass;
        }));
    }
}
