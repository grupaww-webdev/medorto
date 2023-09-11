<?php

declare(strict_types=1);


namespace App\Request\Cart\PutItemToCart;

use App\Application\Cart\PutSimpleItemToCart\PutSimpleItemToCartCommand;
use Symfony\Component\HttpFoundation\Request;
use Sylius\Bundle\CoreBundle\Validator\Constraints\CartItemAvailability;

class PutSimpleItemToCartRequest implements RequestInterface
{
    /** @var int */
    protected $cartId;

    /** @var int */
    protected $productId;

    /**
     * @var int|null
     */
    protected $quantity;

    protected function __construct(
        ?int $cartId,
        ?int $productId,
        ?int $quantity
    ) {
        $this->cartId = $cartId;
        $this->productId = $productId;
        $this->quantity = $quantity;
    }

    public function getProductId(): int
    {
        return $this->productId;
    }

    public function getCartId(): int
    {
        return $this->cartId;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public static function fromArray(array $item): self
    {
        return new self(
            $item['token'] ?? null,
            $item['productCode'] ?? null,
            $item['quantity'] ?? null
        );
    }

    public static function fromHttpRequest(int $cartId, Request $request): RequestInterface
    {
        if ($request->isMethod('GET')) {
            $quantity =  $request->query->getInt('quantity', 1);
        } else {
            if ($request->request->has('sylius_add_to_cart')) {
                $quantity = (int) $request->request->get('sylius_add_to_cart')['cartItem']['quantity'];
            } else {
                $quantity = (int) $request->request->getInt('quantity', 1);
            }
        }

        return new self(
            $cartId,
            $request->query->getInt('productId'),
            $quantity
        );
    }

    public function getCommand(): object
    {
        return new PutSimpleItemToCartCommand(
            $this->cartId,
            $this->productId,
            $this->quantity
        );
    }
}
