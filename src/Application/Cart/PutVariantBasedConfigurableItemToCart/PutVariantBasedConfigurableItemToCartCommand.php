<?php

declare(strict_types=1);


namespace App\Application\Cart\PutVariantBasedConfigurableItemToCart;

use Webmozart\Assert\Assert;

final class PutVariantBasedConfigurableItemToCartCommand
{
    /** @var int */
    protected $cartId;

    /** @var int */
    protected $productId;

    /** @var int */
    protected $quantity;
    /**
     * @var string
     */
    private $variantCode;

    public function __construct(
        int $cartId,
        int $productId,
        string $variantCode,
        int $quantity
    ) {
        Assert::greaterThan($quantity, 0, 'Quantity should be greater than 0');

        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->quantity = $quantity;
        $this->variantCode = $variantCode;
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

    public function getVariantCode(): string
    {
        return $this->variantCode;
    }
}
