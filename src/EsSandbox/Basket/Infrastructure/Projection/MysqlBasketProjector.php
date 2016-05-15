<?php

namespace EsSandbox\Basket\Infrastructure\Projection;

use Doctrine\ORM\EntityManager;
use EsSandbox\Basket\Application\Projection\BasketProjector;
use EsSandbox\Basket\Application\Projection\BasketView;
use EsSandbox\Basket\Application\Projection\ProductView;
use EsSandbox\Basket\Model\BasketWasPickedUp;
use EsSandbox\Basket\Model\ProductWasAddedToBasket;
use EsSandbox\Basket\Model\ProductWasRemovedFromBasket;
use EsSandbox\Common\Model\Event;

class MysqlBasketProjector implements BasketProjector
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
    public function applyBasketWasPickedUp(BasketWasPickedUp $event)
    {
        $this->entityManager->persist(new BasketView($event->id()->toString(), []));
        $this->entityManager->flush();
    }

    /** {@inheritdoc} */
    public function applyProductWasAddedToBasket(ProductWasAddedToBasket $event)
    {
        $basketView = $this->basketView($event);

        if (null === $basketView) {
            return;
        }

        $basketView->products[] = new ProductView($event->productId()->toString(), $event->name());
        $this->entityManager->flush();
    }

    /** {@inheritdoc} */
    public function applyProductWasRemovedFromBasket(ProductWasRemovedFromBasket $event)
    {
        $basketView = $this->basketView($event);

        if (null === $basketView) {
            return;
        }

        foreach ($basketView->products as $key => $productView) {
            if ($productView->productId == $event->productId()) {
                unset($basketView->products[$key]);
            }
        }

        $this->entityManager->flush();
    }

    private function basketView(Event $event)
    {
        return $this->entityManager
            ->getRepository(BasketView::class)
            ->findOneBy(['basketId' => $event->id()->toString()]);
    }
}
