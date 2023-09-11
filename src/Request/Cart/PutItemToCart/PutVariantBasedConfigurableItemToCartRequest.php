<?php

declare(strict_types=1);


namespace App\Request\Cart\PutItemToCart;

use App\Application\Cart\PutVariantBasedConfigurableItemToCart\PutVariantBasedConfigurableItemToCartCommand;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\CoreBundle\Validator\Constraints\CartItemAvailability;

final class PutVariantBasedConfigurableItemToCartRequest implements RequestInterface
{
    /** @var int */
    protected $cartId;
    /** @var int */
    protected $productId;
    /** @var string */
    protected $variantCode;
    /**
     * @var int|null
     */
    protected $quantity;

    protected function __construct(?int $cartId, ?int $productId, ?string $variantCode, ?int $quantity)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->variantCode = $variantCode;
        $this->quantity = $quantity;
    }

    private static function getVariant(Request $request) : string
    {
        if ($request->request->has('variantCode')) {
            return $request->request->get('variantCode');
        }

        return $request->request->get('sylius_add_to_cart')['cartItem']['variant'];
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getCartId(): int
    {
        return $this->cartId;
    }

    public static function fromArray(array $item): self
    {
        return new self(
            $item['token'] ?? null,
            $item['productCode'] ?? null,
            $item['variantCode'] ?? null,
            $item['quantity'] ?? null
        );
    }

    public static function fromHttpRequest(int $cartId, Request $request): RequestInterface
    {
        return new self(
            $cartId,
            $request->query->getInt('productId'),
            self::getVariant($request),
            $request->request->getInt('quantity', 1)
        );
    }

    public function getCommand(): object
    {
        return new PutVariantBasedConfigurableItemToCartCommand(
            $this->cartId,
            $this->productId,
            $this->variantCode,
            $this->quantity
        );
    }
}
