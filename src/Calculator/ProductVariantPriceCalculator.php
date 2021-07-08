<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Product\Product;
use App\Exception\MissingRefundCode;
use App\Exception\MissingUniqueCode;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;

final class ProductVariantPriceCalculator implements ProductVariantPricesCalculatorInterface
{
    /**
     * @var ProductVariantPriceCalculatorInterface
     */
    private $productVariantPriceCalculator;

    public function __construct(ProductVariantPriceCalculatorInterface $productVariantPriceCalculator)
    {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
    }

    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        $price = $this->productVariantPriceCalculator->calculate($productVariant, $context);

        if (false === isset($context['code'])) {
             return $price;
        }

        /** @var Product $product */
        $product = $productVariant->getProduct();
        return $this->calculateRefundCode($product, $price, $context['code']);
    }

    public function calculateOriginal(ProductVariantInterface $productVariant, array $context): int
    {
        $price = $this->productVariantPriceCalculator->calculate($productVariant, $context);

        if (false === isset($context['code'])) {
            return $price;
        }

        /** @var Product $product */
        $product = $productVariant->getProduct();

        return $this->calculateRefundCode($product, $price, $context['code']);
    }

    private function calculateRefundCode(Product $product, int $price, string $code): int
    {
        try {
            $refund = $product->getRefundCode($code);
            return (int) ($price * (1 - $refund->getDiscount() / 100));
        } catch(MissingUniqueCode|MissingRefundCode $exception) {
            return $price;
        }
    }


}
