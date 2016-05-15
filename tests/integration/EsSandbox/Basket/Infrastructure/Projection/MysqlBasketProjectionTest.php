<?php

namespace tests\integration\EsSandbox\Basket\Infrastructure\Projection;

use Doctrine\ORM\EntityManager;
use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Application\Projection\ProductView;
use EsSandbox\Basket\Infrastructure\Projection\MysqlBasketProjection;
use Ramsey\Uuid\Uuid;
use tests\integration\DatabaseTestCase;
use tests\integration\EsSandbox\Basket\Infrastructure\Projection\Dictionary\BasketProjectionDictionary;

class MysqlBasketProjectionTest extends DatabaseTestCase
{
    use BasketProjectionDictionary;

    /** @var MysqlBasketProjection */
    private $projection;

    /** @var EntityManager */
    private $entityManager;

    /** @test */
    public function it_gets_basket_view()
    {
        $this->add(
            new BasketView(
                'afc79dc7-d028-4a4d-8f2c-e6883c9b5e37',
                [
                    new ProductView('fc82f729-d175-4ca7-a061-72f13805612a', 'Iron'),
                    new ProductView('d2ebd152-5713-4e97-b077-1f81374795c2', 'Teapot'),
                ]
            )
        );

        $this->flushAndClear();

        $basketView = $this->projection->get(Uuid::fromString('afc79dc7-d028-4a4d-8f2c-e6883c9b5e37'));

        $this->assertThatBasketViewEquals(
            new BasketView(
                'afc79dc7-d028-4a4d-8f2c-e6883c9b5e37',
                [
                    new ProductView('fc82f729-d175-4ca7-a061-72f13805612a', 'Iron'),
                    new ProductView('d2ebd152-5713-4e97-b077-1f81374795c2', 'Teapot'),
                ]
            ),
            $basketView
        );
    }

    /** @test */
    public function it_gets_null_when_basket_view_does_not_exist()
    {
        $this->flushAndClear();

        $this->assertNull(
            $this->projection->get(Uuid::fromString('afc79dc7-d028-4a4d-8f2c-e6883c9b5e37'))
        );
    }

    /** @test */
    public function it_gets_empty_basket_view_when_no_products()
    {
        $this->add(
            new BasketView('afc79dc7-d028-4a4d-8f2c-e6883c9b5e37', [])
        );

        $this->flushAndClear();

        $basketView = $this->projection->get(Uuid::fromString('afc79dc7-d028-4a4d-8f2c-e6883c9b5e37'));

        $this->assertThatBasketViewEquals(
            new BasketView('afc79dc7-d028-4a4d-8f2c-e6883c9b5e37', []),
            $basketView
        );
    }

    /** {@inheritdoc} */
    protected function setUp()
    {
        parent::setUp();

        $this->projection    = $this->container()->get('es_sandbox.projection.basket.mysql');
        $this->entityManager = $this->container()->get('doctrine.orm.default_entity_manager');
    }

    /** {@inheritdoc} */
    protected function tearDown()
    {
        $this->projection    = null;
        $this->entityManager = null;

        parent::tearDown();
    }

    private function add(BasketView $basketView)
    {
        $this->entityManager->persist($basketView);
    }

    private function flushAndClear()
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }
}
