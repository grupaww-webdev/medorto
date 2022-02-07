<?php

declare(strict_types=1);


namespace App\Request\Cart\PutItemToCart;

use App\Application\Cart\PutOptionBasedConfigurableItemToCart\PutOptionBasedConfigurableItemToCartCommand;
use Symfony\Component\HttpFoundation\Request;

final class PutOptionBasedConfigurableItemToCartRequest implements RequestInterface
{
    protected int $cartId;
    protected int $productId;
    protected array $options;
    protected int $quantity;

    protected function __construct(?int $cartId, ?int $productId, ?array $options, ?int $quantity)
    {
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->options = $options;
        $this->quantity = $quantity;
    }

    private static function getOptions(Request $request) : array
    {
        if ($request->request->has('options')) {
            return $request->request->get('options');
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
            self::getOptions($request),
            $request->request->getInt('quantity', 1)
        );
    }

    public function getCommand(): object
    {
        return new PutOptionBasedConfigurableItemToCartCommand(
            $this->cartId,
            $this->productId,
            $this->options,
            $this->quantity
        );
    }
}
