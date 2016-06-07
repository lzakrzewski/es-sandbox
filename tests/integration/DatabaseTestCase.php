<?php

namespace tests\integration;

use Doctrine\ORM\EntityManager;
use EsSandbox\Basket\Application\Projection\BasketView;

abstract class DatabaseTestCase extends IntegrationTestCase
{
    /** @var EntityManager */
    protected $entityManager;

    protected function add(BasketView $basketView)
    {
        $this->entityManager->persist($basketView);
    }

    protected function flushAndClear()
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->container()
            ->get('es_sandbox.test.doctrine_database_backup')
            ->restore();

        $this->entityManager = $this->container()->get('doctrine.orm.default_entity_manager');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->entityManager = null;

        parent::tearDown();
    }
}
