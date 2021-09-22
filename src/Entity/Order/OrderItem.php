<?php

declare(strict_types=1);

namespace App\Entity\Order;

use App\Entity\Product\ProductRefundInterface;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\OrderItem as BaseOrderItem;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order_item")
 */
class OrderItem extends BaseOrderItem implements OrderItemInterface
{
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Product\ProductRefund")
     * @ORM\JoinColumn(name="product_refund_id", referencedColumnName="id")
     * @var ProductRefundInterface
     */
    private $refund;

    public function getRefund(): ProductRefundInterface
    {
        return $this->refund;
    }

    public function setRefund(ProductRefundInterface $refund): void
    {
        $this->refund = $refund;
    }

    public function hasRefundCode(): bool
    {
        return null !== $this->refund;
    }
}
