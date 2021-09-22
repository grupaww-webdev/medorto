<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\ShippingMethod as BaseShippingMethod;
use Sylius\Component\Shipping\Model\ShippingMethodTranslationInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shipping_method")
 */
class ShippingMethod extends BaseShippingMethod implements PickupPointProviderAwareInterface
{
    use ShippingPointProviderTrait;

    /**
     * @ORM\Column(name="pickup_point_provider", type="string", length=255, nullable=true)
     * @var string|null
     */
    private $pickupPointProvider;

    protected function createTranslation(): ShippingMethodTranslationInterface
    {
        return new ShippingMethodTranslation();
    }
}
