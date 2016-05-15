<?php

namespace EsSandbox\Basket\Application\Projection;

use Doctrine\ORM\Mapping as ORM;

/**
 * @SuppressWarnings(PHPMD.UnusedPrivateField)
 *
 * @ORM\Entity
 * @ORM\Table(name="products")
 */
final class ProductView
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
    public $productId;

    /**
     * @ORM\Column
     *
     * @var string
     */
    public $name;

    /**
     * @param string $productId
     * @param string $name
     */
    public function __construct($productId, $name)
    {
        $this->productId = $productId;
        $this->name      = $name;
    }
}
