<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use Doctrine\ORM\EntityManager;
use EsSandbox\Basket\Application\Projection\BasketProjection;
use EsSandbox\Basket\Application\Projection\BasketView;
use Ramsey\Uuid\UuidInterface;

class MysqlBasketProjection implements BasketProjection
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
    public function get(UuidInterface $basketId)
    {
        return $this->entityManager
            ->getRepository(BasketView::class)
            ->findOneBy(['basketId' => $basketId->toString()]);
    }
}
