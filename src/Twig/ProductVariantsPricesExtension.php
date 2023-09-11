<?php

namespace App\Twig;


use App\Helper\ProductVariantsPricesHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ProductVariantsPricesExtension  extends AbstractExtension
{
    /** @var ProductVariantsPricesHelper */
    private $productVariantsPricesHelper;

    public function __construct(ProductVariantsPricesHelper $productVariantsPricesHelper)
    {
        $this->productVariantsPricesHelper = $productVariantsPricesHelper;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('sylius_product_variant_prices', [$this->productVariantsPricesHelper, 'getPrices']),
            new TwigFunction('sylius_product_variant_quantity', [$this->productVariantsPricesHelper, 'quantity']),
            new TwigFunction('sylius_product_variant_trackable', [$this->productVariantsPricesHelper, 'trackable']),
        ];
    }
}
