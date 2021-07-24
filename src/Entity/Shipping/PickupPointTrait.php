<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

/**
 * @property PickupPoint|null $pickupPoint
 */
trait PickupPointTrait
{
    public function hasPickupPoint(): bool
    {
        return $this->pickupPoint !== null;
    }

    public function setPickupPoint(?PickupPoint $pickupPoint): void
    {
        $this->pickupPoint = $pickupPoint;
    }

    public function getPickupPoint(): ?PickupPoint
    {
        return $this->pickupPoint;
    }
}
