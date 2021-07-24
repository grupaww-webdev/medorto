<?php

declare(strict_types=1);


namespace App\Entity\Shipping;

interface PickupPointAwareInterface
{
    public function hasPickupPoint(): bool;
    public function setPickupPoint(?PickupPoint $pickupPoint): void;
    public function getPickupPoint(): ?PickupPoint;
}
