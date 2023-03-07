<?php

namespace App\Twig;


use App\Helper\PriceHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

final class PriceExtension extends AbstractExtension
{
    /** @var PriceHelper */
    private PriceHelper $helper;

    public function __construct(PriceHelper $helper)
    {
        $this->helper = $helper;
    }

    public function getFilters(): array
    {
        return [
            new TwigFilter('sylius_calculate_price', [$this->helper, 'getPrice']),
            new TwigFilter('sylius_calculate_original_price', [$this->helper, 'getOriginalPrice']),
            new TwigFilter('sylius_calculate_minimum_price', [$this->helper, 'getMinimumPrice']),
            new TwigFilter('sylius_has_discount', [$this->helper, 'hasDiscount']),
        ];
    }
}
