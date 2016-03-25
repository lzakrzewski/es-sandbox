<?php

namespace tests\contexts;

use Assert\Assertion;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Symfony2Extension\Context\KernelAwareContext;
use Behat\Symfony2Extension\Context\KernelDictionary;
use EsSandbox\Basket\Infrastructure\Projection\InMemoryStorage;
use EsSandbox\Basket\Model\BasketId;
use EsSandbox\Basket\Model\ProductId;
use EsSandbox\Common\Application\CommandBus\Command;
use EsSandbox\Common\Model\Event;

class DefaultContext implements KernelAwareContext, SnippetAcceptingContext
{
    use KernelDictionary;

    /** @var mixed */
    protected $view;

    /** @BeforeScenario */
    public function beforeScenario()
    {
        InMemoryStorage::instance()->clear();
    }

    /** @AfterScenario */
    public function afterScenario()
    {
        $this->view = null;
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
        return BasketId::fromString($basketId);
    }

    /**
     * @Transform :productId
     */
    public function productId($productId)
    {
        return ProductId::fromString($productId);
    }

    protected function given(Event $event)
    {
        $this->container()->get('event_bus')->handle($event);
    }

    protected function when(Command $command)
    {
        $this->container()->get('es_sandbox.command_bus')->handle($command);
    }

    protected function see($view)
    {
        $this->view = $view;
    }

    protected function then($expectedEventClass)
    {
        $events = $this->container()->get('es_sandbox.event_store')->events();

        Assertion::notEmpty(array_filter($events, function (Event $event) use ($expectedEventClass) {
            return $event instanceof $expectedEventClass;
        }));
    }
}
