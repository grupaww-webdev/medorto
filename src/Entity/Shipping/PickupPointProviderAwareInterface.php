<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

interface PickupPointProviderAwareInterface
{
    public function hasPickupPointProvider(): bool;
    public function getPickupPointProvider(): ?string;
}
