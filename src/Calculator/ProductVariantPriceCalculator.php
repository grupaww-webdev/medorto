<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Product\Product;
use App\Exception\MissingRefundCode;
use App\Exception\MissingUniqueCode;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Webmozart\Assert\Assert;

final class ProductVariantPriceCalculator implements ProductVariantPricesCalculatorInterface
{
    /**
     * @var ProductVariantPriceCalculatorInterface|/Sylius/ ProductVariantPriceCalculator
     */
    private $productVariantPriceCalculator;

    public function __construct(ProductVariantPriceCalculatorInterface $productVariantPriceCalculator)
    {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
    }

    public function calculate(ProductVariantInterface $productVariant, array $context): int
    {
        $price = $this->productVariantPriceCalculator->calculate($productVariant, $context);

        if (true === isset($context['minimum'])) {
            return $this->calculateMinimum($productVariant, $context);
        }

        if (false === isset($context['code'])) {
             return $price;
        }

        /** @var Product $product */
        $product = $productVariant->getProduct();
        return $this->calculateRefundCode($product, $price, $context['code']);
    }

    public function calculateOriginal(ProductVariantInterface $productVariant, array $context): int
    {
        $price = $this->productVariantPriceCalculator->calculateOriginal($productVariant, $context);

        if (false === isset($context['code'])) {
            return $price;
        }

        /** @var Product $product */
        $product = $productVariant->getProduct();

        return $this->calculateRefundCode($product, $price, $context['code']);
    }

    public function calculateMinimum(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');

        $channelPricing = $productVariant->getChannelPricingForChannel($context['channel']);
        if (null === $channelPricing) {
            $message = sprintf('Channel %s has no price defined for product variant', $context['channel']->getName());
            if ($productVariant->getName() !== null) {
                $message .= sprintf(' %s (%s)', $productVariant->getName(), $productVariant->getCode());
            } else {
                $message .= sprintf(' with code %s', $productVariant->getCode());
            }
            throw new MissingChannelConfigurationException($message);
        }
        return $channelPricing->lastMinimumPrice();
    }

    public function calculateRefund(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');
        Assert::keyExists($context, 'code');

        $channelPricing = $productVariant->getChannelPricingForChannel($context['channel']);
        if (null === $channelPricing) {
            $message = sprintf('Channel %s has no price defined for product variant', $context['channel']->getName());
            if ($productVariant->getName() !== null) {
                $message .= sprintf(' %s (%s)', $productVariant->getName(), $productVariant->getCode());
            } else {
                $message .= sprintf(' with code %s', $productVariant->getCode());
            }
            throw new MissingChannelConfigurationException($message);
        }
        return $channelPricing->lastMinimumPrice();
    }

    private function calculateRefundCode(Product $product, int $price, string $code): int
    {
        try {
            $refund = $product->getRefundCode($code);
            return (int) ($price - $refund->getDiscountPack());
            return (int) ($price * (1 - $refund->getDiscount() / 100));
        } catch(MissingUniqueCode|MissingRefundCode $exception) {
            return $price;
        }
    }



}
