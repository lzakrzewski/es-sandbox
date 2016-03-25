<?php

namespace tests\integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class IntegrationTestCase extends WebTestCase
{
    /** @var ContainerInterface */
    private $container;

    /**
     * @return ContainerInterface
     */
    protected function container()
    {
        return $this->container;
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        $this->container = $this->createClient()->getContainer();
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->container = null;

        parent::tearDown();
    }
}
