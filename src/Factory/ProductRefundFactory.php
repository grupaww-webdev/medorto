<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Product\ProductRefund;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductRefundFactory implements FactoryInterface
{
    public function createNew(): ProductRefund
    {
        return new ProductRefund('', 0, null);
    }

    public function createForProduct(ProductInterface $product): ProductRefund
    {
        return new ProductRefund('', 0, $product);
    }
}
