<?php

declare(strict_types=1);

namespace App\Processor;

use App\Calculator\ProductVariantPriceCalculator;
use App\Entity\Order\OrderItemInterface;
use Sylius\Component\Order\Model\OrderInterface as BaseOrderInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class OrderItemRefundCodeCalculator implements OrderProcessorInterface
{
    /**
     * @var ProductVariantPriceCalculator
     */
    private $productVariantPricesCalculator;

    public function __construct(
        ProductVariantPriceCalculator $productVariantPricesCalculator
    ) {
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
    }

    public function process(BaseOrderInterface $order): void
    {
        /** @var OrderItemInterface $item */
        foreach ($order->getItems() as $item) {
            if (true === $item->hasRefundCode()) {
                $item->setUnitPrice($this->productVariantPricesCalculator->calculate($item->getVariant(), [
                    'channel' => $order->getChannel(),
                    'code' => $item->getRefund()->getCode()
                ]));
            }
        }
    }
}
