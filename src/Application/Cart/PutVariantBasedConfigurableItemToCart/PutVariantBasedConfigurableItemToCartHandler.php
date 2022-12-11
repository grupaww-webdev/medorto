<?php

declare(strict_types=1);

namespace App\Application\Cart\PutVariantBasedConfigurableItemToCart;

use App\Entity\Order\OrderItemInterface;
use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductVariantInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Repository\ProductVariantRepositoryInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Modifier\OrderModifierInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Webmozart\Assert\Assert;

final class PutVariantBasedConfigurableItemToCartHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $orderRepository;
    private ProductRepositoryInterface $productRepository;
    private OrderModifierInterface $orderModifier;
    private CartItemFactoryInterface $orderItemFactory;
    private OrderItemQuantityModifierInterface $orderQuantityModifier;
    private ProductVariantRepositoryInterface $productVariantRepository;

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
        ProductVariantRepositoryInterface $productVariantRepository,
        AvailabilityCheckerInterface $availabilityChecker
    ) {
        $this->orderRepository = $orderRepository;
        $this->productRepository = $productRepository;
        $this->orderModifier = $orderModifier;
        $this->orderQuantityModifier = $orderQuantityModifier;
        $this->orderItemFactory = $orderItemFactory;
        $this->productVariantRepository = $productVariantRepository;
        $this->availabilityChecker = $availabilityChecker;
    }

    public function __invoke(PutVariantBasedConfigurableItemToCartCommand $command): int
    {
        /** @var OrderInterface $cart */
        $cart = $this->orderRepository->find($command->getCartId());

        Assert::notNull($cart, 'Cart has not been found');

        /** @var ProductInterface $product */
        $product = $this->productRepository->find($command->getProductId());
        Assert::notNull($product, 'Product has not been found');

        /** @var ProductVariantInterface $productVariant */
        $productVariant = $this->productVariantRepository->findOneByCodeAndProductCode($command->getVariantCode(), $product->getCode());
        Assert::false($product->isSimple(), 'Product has to be simple');

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
