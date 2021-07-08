<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Exception\MissingRefundCode;
use App\Exception\MissingUniqueCode;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct
{
    /**
     * @var Collection|array<ProductRefundInterface>
     * @ORM\OneToMany(targetEntity="ProductRefund", mappedBy="product", cascade={"persist", "remove", "merge"})
     */
    private $refunds;

    public function __construct()
    {
        parent::__construct();
        $this->refunds = new ArrayCollection();
    }

    protected function createTranslation(): ProductTranslationInterface
    {
        return new ProductTranslation();
    }

    public function hasRefundCodes(): bool
    {
        return $this->refunds->filter(function(ProductRefundInterface $refund) {
            return $refund->isActive();
        })->count() > 0;
    }

    public function getRefundCode(string $code): ProductRefundInterface
    {
        $refundCode = $this->refunds->filter(function(ProductRefundInterface $refund) use ($code) {
            return $refund->isActive() && $refund->getCode() === $code;
        });

        if (0 === $refundCode->count()) {
            throw new MissingRefundCode();
        }

        if (1 < $refundCode->count()) {
            throw new MissingUniqueCode();
        }

        return $refundCode->first();
    }

    public function getRefunds()
    {
        return $this->refunds;
    }
}
