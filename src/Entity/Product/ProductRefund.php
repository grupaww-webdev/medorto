<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity()
 * @ORM\Table(name="sylius_product_refunds")
 */
class ProductRefund implements ResourceInterface, ProductRefundInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    private $code;

    /**
     * @ORM\Column(type="decimal", precision=5, scale=2)
     * @var float
     */
    private $discount;

    /**
     * @ORM\ManyToOne(targetEntity="Product", inversedBy="refunds")
     * @ORM\JoinColumn(name="product_id", referencedColumnName="id")
     * @var ProductInterface
     */
    private $product;

    /**
     * @ORM\Column(name="active", type="boolean", options={"default" : false})
     * @var bool
     */
    private $active;

    /**
     * @ORM\Column(name="description", type="text")
     * @var string
     */
    private $description;

    public function __construct(
        string $code,
        float $discount,
        ?ProductInterface $product
    ) {
        $this->code = $code;
        $this->discount = $discount;
        $this->product = $product;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getDiscount(): float
    {
        return (float)$this->discount;
    }

    public function getProduct(): ProductInterface
    {
        return $this->product;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function setDiscount(float $discount): void
    {
        $this->discount = (float)$discount;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }
}
