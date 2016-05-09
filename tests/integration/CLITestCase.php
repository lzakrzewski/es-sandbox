<?php

namespace tests\integration;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;

abstract class CLITestCase extends IntegrationTestCase
{
    /** @var CommandTester */
    private $tester;

    /**
     * @param Command $command
     * @param array   $parameters
     */
    protected function executeCommand(Command $command, $parameters = [])
    {
        $application = new Application($this->container()->get('kernel'));
        $application->add($command);

        $this->tester = new CommandTester($command);

        $parameters = array_merge(
            ['command' => $command->getName()],
            $parameters
        );

        $this->tester->execute($parameters);
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->tester = null;

        parent::tearDown();
    }

    protected function outputShouldStatusCodeIs($expectedStatus)
    {
        $this->assertEquals($expectedStatus, $this->tester->getStatusCode());
    }

    protected function given(array $events)
    {
        $this->container()->get('es_sandbox.event_store')->commit($events);
    }
}
