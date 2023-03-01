<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Order as BaseOrder;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_order")
 */
class Order extends BaseOrder
{
    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $refundPesel;

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected ?string $refundCode;

    public function __toString(): string
    {
        return $this->number ?? '';
    }

    public function hasRefundItems(): bool
    {
        /** @var \App\Entity\Order\OrderItem $item */
        foreach ($this->items as $item)
        {
            if($item->hasRefundCode())
                return true;
        }
        return false;
    }

    /**
     * @return string|null
     */
    public function getRefundPesel(): ?string
    {
        return $this->refundPesel;
    }

    /**
     * @param   string|null  $refundPesel
     *
     * @return Order
     */
    public function setRefundPesel(?string $refundPesel): Order
    {
        $this->refundPesel = $refundPesel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRefundCode(): ?string
    {
        return $this->refundCode;
    }

    /**
     * @param   string|null  $refundCode
     *
     * @return Order
     */
    public function setRefundCode(?string $refundCode): Order
    {
        $this->refundCode = $refundCode;

        return $this;
    }


}
