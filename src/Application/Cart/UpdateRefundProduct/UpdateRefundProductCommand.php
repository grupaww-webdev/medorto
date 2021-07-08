<?php

declare(strict_types=1);

namespace App\Application\Cart\UpdateRefundProduct;

final class UpdateRefundProductCommand
{
    private $productId;
    private $productVariantId;
    private $refundCode;

    public function __construct(int $productId, int $productVariantId, string $refundCode)
    {
        $this->productId = $productId;
        $this->productVariantId = $productVariantId;
        $this->refundCode = $refundCode;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getProductVariantId(): int
    {
        return $this->productVariantId;
    }

    public function getRefundCode(): string
    {
        return $this->refundCode;
    }
}
