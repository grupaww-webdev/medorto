<?php

declare(strict_types=1);


namespace App\Entity\Order;


use App\Entity\Product\ProductRefundInterface;
use \Sylius\Component\Core\Model\OrderItemInterface as BaseOrderItemInterface;


interface OrderItemInterface extends BaseOrderItemInterface
{
    public function getRefund(): ?ProductRefundInterface;
    public function setRefund(ProductRefundInterface $refund): void;
    public function hasRefundCode(): bool;
}
