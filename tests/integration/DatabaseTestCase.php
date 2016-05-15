<?php

namespace tests\integration;

abstract class DatabaseTestCase extends IntegrationTestCase
{
    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->container()
            ->get('es_sandbox.test.doctrine_database_backup')
            ->restore();
    }
}
