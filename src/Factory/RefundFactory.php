<?php

declare(strict_types=1);

namespace App\Factory;

use App\Entity\Order\Order;
use App\Entity\Order\Refund;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class RefundFactory implements FactoryInterface
{
    public function createNew(): Refund
    {
        return new Refund();
    }

    public function createNewForOrder(Order $order): Refund
    {
        $refund = new Refund();
        $refund->setOrder($order);

        return $refund;
    }
}
