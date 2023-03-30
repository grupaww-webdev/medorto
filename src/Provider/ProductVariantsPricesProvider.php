<?php

namespace App\Provider;

use App\Calculator\ProductVariantPriceCalculator;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Provider\ProductVariantsPricesProviderInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;

final class ProductVariantsPricesProvider implements
    ProductVariantsPricesProviderInterface
{

    /** @var ProductVariantPriceCalculator */
    private ProductVariantPriceCalculator $productVariantPriceCalculator;

    /**
     * @var \Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface
     */
    private AvailabilityCheckerInterface $availabilityChecker;

    public function __construct(
        ProductVariantPriceCalculator $productVariantPriceCalculator,
        AvailabilityCheckerInterface $availabilityChecker
    ) {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
        $this->availabilityChecker = $availabilityChecker;
    }

    public function provideVariantsPrices(
        ProductInterface $product,
        ChannelInterface $channel
    ): array {
        $variantsPrices = [];

        /** @var ProductVariantInterface $variant */
        foreach ($product->getEnabledVariants() as $variant) {
            if($this->availabilityChecker->isStockAvailable($variant))
            $variantsPrices[] = $this->constructOptionsMap($variant, $channel);
        }

        return $variantsPrices;
    }

    private function constructOptionsMap(
        ProductVariantInterface $variant,
        ChannelInterface $channel
    ): array {
        $optionMap = [];

        /** @var ProductOptionValueInterface $option */
        foreach ($variant->getOptionValues() as $option) {
            $optionMap[$option->getOptionCode()] = $option->getCode();
        }

//        dump($variant);die;

        $price = $this->productVariantPriceCalculator->calculate(
            $variant,
            ['channel' => $channel]
        );
        $optionMap['value'] = $price;

        if ($this->productVariantPriceCalculator instanceof ProductVariantPricesCalculatorInterface) {
            $originalPrice = $this->productVariantPriceCalculator->calculateOriginal(
                $variant,
                ['channel' => $channel]
            );

            if ($originalPrice > $price) {
                $optionMap['original-price'] = $originalPrice;

                $minimumPrice = $this->productVariantPriceCalculator->calculateMinimum(
                    $variant,
                    ['channel' => $channel]
                );

                $optionMap['minimum-price'] = $minimumPrice;
            }
        }

        return $optionMap;
    }

}
