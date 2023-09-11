<?php

namespace App\Helper;

use Sylius\Component\Inventory\Model\StockableInterface;

class ProductVariantsPricesHelper extends \Sylius\Bundle\CoreBundle\Templating\Helper\ProductVariantsPricesHelper
{
    public function quantity(StockableInterface $stockable): int
    {
        return  ($stockable->getOnHand() - $stockable->getOnHold());
    }

    public function trackable(StockableInterface $stockable): bool
    {
        return $stockable->isTracked();
    }
}
