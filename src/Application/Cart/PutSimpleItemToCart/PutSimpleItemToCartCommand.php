<?php

declare(strict_types=1);

namespace App\Application\Cart\PutSimpleItemToCart;

use Webmozart\Assert\Assert;

final class PutSimpleItemToCartCommand
{
    /** @var int */
    protected $cartId;

    /** @var int */
    protected $productId;

    /** @var int */
    protected $quantity;

    public function __construct(int $orderToken, int $productId, int $quantity)
    {
        Assert::greaterThan($quantity, 0, 'Quantity should be greater than 0');

        $this->cartId = $orderToken;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getCartId(): int
    {
        return $this->cartId;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }
}
