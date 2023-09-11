<?php

declare(strict_types=1);


namespace App\Request\Cart\PutItemToCart;

use App\Application\Cart\PutOptionBasedConfigurableItemToCart\PutOptionBasedConfigurableItemToCartCommand;
use App\Entity\Order\OrderItem;
use Doctrine\Common\Annotations\Annotation\IgnoreAnnotation;
use Sylius\Bundle\OrderBundle\Controller\AddToCartCommandInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\OrderItemInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use Sylius\Bundle\CoreBundle\Validator\Constraints\CartItemAvailability;

final class PutOptionBasedConfigurableItemToCartRequest implements RequestInterface // , AddToCartCommandInterface
{
    protected int $cartId;
    protected int $productId;
    protected array $options;

    /**
     * @var int|null
     */
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

    public static function getQuantity(Request $request): int
    {
        return (int)$request->request->get('sylius_add_to_cart')['cartItem']['quantity'] ?? 1;
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
            self::getQuantity($request)
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
