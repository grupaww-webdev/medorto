<?php

declare(strict_types=1);


namespace App\Entity\Product;


use Sylius\Component\Core\Model\ProductInterface;

interface ProductRefundInterface
{
    public function getCode(): string;
    public function getDiscount(): float;
    public function getDiscountPiece(): int;
    public function getDiscountPack(): int;
    public function getProduct(): ProductInterface;
    public function setCode(string $code): void;
    public function setDiscount(float $discount): void;
    public function setDiscountPiece(float $discountPiece): void;
    public function setDiscountPack(float $discountPack): void;
    public function setActive(bool $active): void;
    public function isActive(): bool;
    public function getDescription(): string;
    public function setDescription(string $description): void;
    public function count(): int;
}
