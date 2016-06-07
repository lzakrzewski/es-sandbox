<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection;

use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Infrastructure\Projection\MysqlLastBasketIdProjection;
use Ramsey\Uuid\Uuid;
use tests\integration\DatabaseTestCase;

class MysqlLastBasketIdProjectionTest extends DatabaseTestCase
{
    /** @var MysqlLastBasketIdProjection */
    private $projection;

    /** @test */
    public function it_returns_last_basket_id()
    {
        $this->add(new BasketView('afc79dc7-d028-4a4d-8f2c-e6883c9b5e36', []));
        $this->add(new BasketView('afc79dc7-d028-4a4d-8f2c-e6883c9b5e37', []));

        $this->flushAndClear();

        $this->assertEquals(Uuid::fromString('afc79dc7-d028-4a4d-8f2c-e6883c9b5e37'), $this->projection->get());
    }

    /** @test */
    public function it_returns_null_when_no_baskets()
    {
        $this->assertNull($this->projection->get());
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->projection = $this->container()->get('es_sandbox.projection.last_basket_id.mysql');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->projection = null;

        parent::tearDown();
    }
}
