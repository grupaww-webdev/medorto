<?php

declare(strict_types=1);

namespace App\PickupPoint\Manager;

use App\PickupPoint\Provider\PickupPointProviderInterface;

interface ProviderManagerInterface
{
    /**
     * @return PickupPointProviderInterface[]
     */
    public function all(): array;
    public function addProvider(PickupPointProviderInterface $pickupPointProvider): void;
    public function findByCode(string $code): ?PickupPointProviderInterface;
    public function findByClassName(string $class): ?PickupPointProviderInterface;
}
