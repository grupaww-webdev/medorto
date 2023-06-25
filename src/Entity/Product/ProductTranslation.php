<?php

declare(strict_types=1);

namespace App\Entity\Product;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ProductTranslation as BaseProductTranslation;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_product_translation")
 */
class ProductTranslation extends BaseProductTranslation
{
    /**
     * @var string
     * @ORM\Column(type="string", nullable=true)
     * @Assert\Type(type="boolean")
     */
    protected $shippingLabel;

    /**
     * @return string
     */
    public function getShippingLabel(): ?string
    {
        return $this->shippingLabel;
    }

    /**
     * @param string $shippingLabel
     */
    public function setShippingLabel(?string $shippingLabel): void
    {
        $this->shippingLabel = $shippingLabel;
    }

}
