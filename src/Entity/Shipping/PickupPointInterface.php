<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

use Ramsey\Uuid\UuidInterface;

interface PickupPointInterface
{
    public function getId(): UuidInterface;
    public function getName(): string;
    public function getAddress(): string;
    public function getZipCode(): string;
    public function getCity(): string;
    public function isActive(): bool;
    public function deactivate(): void;
    public function activate(): void;
    public function serialize(): array;
}
