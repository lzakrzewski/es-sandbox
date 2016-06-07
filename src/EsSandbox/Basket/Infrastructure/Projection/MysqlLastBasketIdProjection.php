<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use Doctrine\ORM\EntityManager;
use EsSandbox\Basket\Application\Projection\LastBasketIdProjection;
use Ramsey\Uuid\Uuid;

class MysqlLastBasketIdProjection implements LastBasketIdProjection
{
    /** @var EntityManager */
    private $entityManager;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /** {@inheritdoc} */
    public function get()
    {
        $result = $this
            ->entityManager
            ->createQuery('SELECT bv.basketId FROM EsSandbox\Basket\Application\Projection\BasketView bv ORDER BY bv.id DESC')
            ->setMaxResults(1)
            ->getResult();

        if (empty($result)) {
            return;
        }

        return Uuid::fromString($result[0]['basketId']);
    }
}
