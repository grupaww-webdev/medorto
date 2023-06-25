<?php

declare(strict_types=1);

namespace App\Entity\Product;

use App\Exception\MissingRefundCode;
use App\Exception\MissingUniqueCode;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use MonsieurBiz\SyliusSearchPlugin\Model\Documentable\DocumentableInterface;
use Odiseo\SyliusVendorPlugin\Entity\VendorAwareInterface;
use Odiseo\SyliusVendorPlugin\Entity\VendorTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;
use Sylius\Component\Product\Model\ProductTranslationInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product")
 */
class Product extends BaseProduct implements ProductInterface, VendorAwareInterface, DocumentableInterface
{
    use VendorTrait;
    use DocumentableProductTrait;

    /**
     * @var Collection|array<ProductRefundInterface>
     * @ORM\OneToMany(targetEntity="ProductRefund", mappedBy="product", cascade={"persist", "remove", "merge"})
     */
    private $refunds;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     * @Assert\Type(type="boolean")
     */
    protected $bestseller = false;

    /**
     * @var bool
     * @ORM\Column(type="boolean", options={"default": false})
     * @Assert\Type(type="boolean")
     */
    protected $new = false;

    public function __construct()
    {
        parent::__construct();
        $this->refunds = new ArrayCollection();
    }

    /**
     * @return bool
     */
    public function isBestseller(): bool
    {
        return $this->bestseller;
    }

    /**
     * @param bool $bestseller
     */
    public function setBestseller(bool $bestseller): void
    {
        $this->bestseller = $bestseller;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->new;
    }

    /**
     * @param bool $new
     */
    public function setNew(bool $new): void
    {
        $this->new = $new;
    }

    public function getMetaDescription(): ?string
    {
        return $this->getTranslation()->getMetaDescription();
    }

    public function setMetaDescription(?string $metaDescription): void
    {
        $this->getTranslation()->setMetaDescription($metaDescription);
    }

    /**
     * @return string
     */
    public function getShippingLabel(): ?string
    {
        return $this->getTranslation()->getShippingLabel();
    }

    /**
     * @param string $shippingLabel
     */
    public function setShippingLabel(?string $shippingLabel): void
    {
        $this->getTranslation()->setShippingLabel($shippingLabel);
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

    public function getRefunds(): Collection
    {
        return $this->refunds;
    }
}
