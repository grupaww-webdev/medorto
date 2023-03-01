<?php

namespace App\Helper;

use App\Calculator\ProductVariantPriceCalculator;
use Sylius\Component\Core\Calculator\ProductVariantPriceCalculatorInterface;
use Sylius\Component\Core\Calculator\ProductVariantPricesCalculatorInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Symfony\Component\Templating\Helper\Helper;
use Webmozart\Assert\Assert;

class PriceHelper extends \Sylius\Bundle\CoreBundle\Templating\Helper\PriceHelper
{
    /** @var ProductVariantPriceCalculator */
    protected ProductVariantPriceCalculator $productVariantPriceCalculator;

    public function __construct(ProductVariantPriceCalculator $productVariantPriceCalculator)
    {
        $this->productVariantPriceCalculator = $productVariantPriceCalculator;
        parent::__construct($productVariantPriceCalculator);
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getMinimumPrice(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');
        Assert::isInstanceOf($this->productVariantPriceCalculator, ProductVariantPricesCalculatorInterface::class);

        return $this
            ->productVariantPriceCalculator
            ->calculateMinimum($productVariant, $context)
            ;
    }

    /**
     * @throws \InvalidArgumentException
     */
    public function getRefundPrice(ProductVariantInterface $productVariant, array $context): int
    {
        Assert::keyExists($context, 'channel');
        Assert::isInstanceOf($this->productVariantPriceCalculator, ProductVariantPricesCalculatorInterface::class);

        return $this
            ->productVariantPriceCalculator
            ->calculateMinimum($productVariant, $context)
            ;
    }
}
