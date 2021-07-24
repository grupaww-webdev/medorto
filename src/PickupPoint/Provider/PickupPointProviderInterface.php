<?php

declare(strict_types=1);

namespace App\PickupPoint\Provider;

use Sylius\Component\Core\Model\OrderInterface;

interface PickupPointProviderInterface
{
    public function getName(): string;
    public function getCode(): string;
    public function findPickupPoints(OrderInterface $order): array;
}
