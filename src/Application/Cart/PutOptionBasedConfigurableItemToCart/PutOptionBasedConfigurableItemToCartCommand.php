<?php

declare(strict_types=1);


namespace App\Application\Cart\PutOptionBasedConfigurableItemToCart;

use Webmozart\Assert\Assert;

final class PutOptionBasedConfigurableItemToCartCommand
{
    private array $options;
    private int $cartId;
    private int $quantity;
    private int $productId;

    public function __construct(
        int $cartId,
        int $productId,
        array $options,
        int $quantity
    ) {
        Assert::greaterThan($quantity, 0, 'Quantity should be greater than 0');

        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->options = $options;
    }

    public function getOptions(): array
    {
        return $this->options;
    }

    public function getCartId(): int
    {
        return $this->cartId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }
}
