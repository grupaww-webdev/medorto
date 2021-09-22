<?php

declare(strict_types=1);


namespace App\Entity\Product;

use Doctrine\Common\Collections\Collection;

interface ProductInterface extends \Sylius\Component\Core\Model\ProductInterface
{
    public function hasRefundCodes(): bool;

    public function getRefundCode(string $code): ProductRefundInterface;

    public function getRefunds(): Collection;
}
