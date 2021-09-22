<?php

declare(strict_types=1);

namespace App\PickupPoint\Factory;

use App\Entity\Shipping\PickupPointInterface;

interface PickupPointFactoryInterface
{
    public function createNew(): PickupPointInterface;
}
