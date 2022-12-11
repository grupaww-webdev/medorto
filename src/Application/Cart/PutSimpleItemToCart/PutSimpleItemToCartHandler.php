<?php

declare(strict_types=1);

namespace App\Application\Cart\PutSimpleItemToCart;

use App\Entity\Order\OrderItemInterface;
use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class PutSimpleItemToCartHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $orderRepository;
    private ProductRepositoryInterface $productRepository;
    private OrderModifierInterface $orderModifier;
    private CartItemFactoryInterface $orderItemFactory;
    private OrderItemQuantityModifierInterface $orderQuantityModifier;

    /**
     * @var \Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface
     */
    private AvailabilityCheckerInterface $availabilityChecker;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        ProductRepositoryInterface $productRepository,
        CartItemFactoryInterface $orderItemFactory,
        OrderModifierInterface $orderModifier,
        OrderItemQuantityModifierInterface $orderQuantityModifier,
        AvailabilityCheckerInterface $availabilityChecker
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->orderModifier = $orderModifier;
        $this->orderQuantityModifier = $orderQuantityModifier;
        $this->orderItemFactory = $orderItemFactory;
        $this->availabilityChecker = $availabilityChecker;
    }

    public function __invoke(PutSimpleItemToCartCommand $command): int
    {
        /** @var OrderInterface $cart */
        $cart = $this->orderRepository->find($command->getCartId());

        Assert::notNull($cart, 'Cart has not been found');

        /** @var ProductInterface $product */
        $product = $this->productRepository->find($command->getProductId());
        Assert::notNull($product, 'Product has not been found');

        Assert::true($product->isSimple(), 'Product has to be simple');

        $productVariant = $product->getVariants()[0];

        $isStockSufficient = $this->availabilityChecker->isStockSufficient($productVariant, $command->getQuantity());
        Assert::true($isStockSufficient, 'Product does not have sufficient stock.');

        /** @var OrderItemInterface $cartItem */
        $cartItem = $this->orderItemFactory->createForProduct($product);
        $cartItem->setVariant($productVariant);
        $this->orderQuantityModifier->modify($cartItem, $command->getQuantity());
        $this->orderModifier->addToOrder($cart, $cartItem);

        return $productVariant->getId();
    }
}
