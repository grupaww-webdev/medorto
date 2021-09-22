<?php

declare(strict_types=1);

namespace App\PickupPoint\Factory;

use App\Entity\Shipping\PickupPoint;
use App\Entity\Shipping\PickupPointInterface;
use Ramsey\Uuid\Uuid;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class PickupPointFactory implements FactoryInterface, PickupPointFactoryInterface
{
    public function createNew(): PickupPointInterface
    {
        return new PickupPoint(Uuid::uuid4());
    }
}
