<?php

namespace EsSandbox\Basket\Application\Projection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @SuppressWarnings(PHPMD.UnusedPrivateField)
 *
 * @ORM\Entity
 * @ORM\Table(name="baskets")
 */
final class BasketView
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer", name="id")
     * @ORM\GeneratedValue(strategy="AUTO")
     *
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="guid")
     *
     * @var string
     */
    public $basketId;

    /**
     * @var ProductView[]
     *
     * @ORM\ManyToMany(targetEntity="ProductView", cascade={"ALL"}, orphanRemoval=true, fetch="EAGER")
     * @ORM\JoinTable(
     *      name="basket_products",
     *      joinColumns={@ORM\JoinColumn(name="basket_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="product_id", referencedColumnName="id", unique=true)}
     * )
     */
    public $products;

    /**
     * @param string        $basketId
     * @param ProductView[] $products
     */
    public function __construct($basketId, array $products)
    {
        $this->basketId = $basketId;
        $this->products = $products;
    }
}
