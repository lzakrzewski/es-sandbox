<?php

namespace tests\unit\EsSandbox\Common\Infrastructure\CommandBus;

use EsSandbox\Common\Application\CommandBus\Command;
use EsSandbox\Common\Infrastructure\CommandBus\SimpleBusCommandBus;
use PhpSpec\ObjectBehavior;
use SimpleBus\Message\Bus\MessageBus;

/**
 * @mixin SimpleBusCommandBus
 */
class SimpleBusCommandBusSpec extends ObjectBehavior
{
    public function let(MessageBus $simpleBus)
    {
        $this->beConstructedWith($simpleBus);
    }

    public function it_delegates_command_to_simple_bus(Command $command, MessageBus $simpleBus)
    {
        $command = $command->getWrappedObject();
        $simpleBus->handle($command)->shouldBeCalled();

        $this->handle($command);
    }
}
