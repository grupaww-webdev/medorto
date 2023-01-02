<?php

declare(strict_types=1);

namespace App\Application\Cart\PutOptionBasedConfigurableItemToCart;

use App\Entity\Order\OrderItemInterface;
use Sylius\Bundle\InventoryBundle\Validator\Constraints\InStockValidator;
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

final class PutOptionBasedConfigurableItemToCartHandler implements MessageHandlerInterface
{
    private OrderRepositoryInterface $orderRepository;
    private ProductRepositoryInterface $productRepository;
    private OrderModifierInterface $orderModifier;
    private CartItemFactoryInterface $orderItemFactory;
    private OrderItemQuantityModifierInterface $orderQuantityModifier;
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

    public function __invoke(PutOptionBasedConfigurableItemToCartCommand $command): int
    {
        /** @var OrderInterface $cart */
        $cart = $this->orderRepository->find($command->getCartId());

        Assert::notNull($cart, 'Cart has not been found');

        /** @var ProductInterface $product */
        $product = $this->productRepository->find($command->getProductId());
        Assert::notNull($product, 'Product has not been found');

        $productVariant = $this->getVariant($command->getOptions(), $product);
        Assert::false($product->isSimple(), 'Product has to be simple');

        $isStockSufficient = $this->availabilityChecker->isStockSufficient($productVariant, $command->getQuantity() );
        Assert::true($isStockSufficient, 'Product does not have sufficient stock.');

        /** @var OrderItemInterface $cartItem */
        $cartItem = $this->orderItemFactory->createForProduct($product);
        $cartItem->setVariant($productVariant);
        $this->orderQuantityModifier->modify($cartItem, $command->getQuantity());
        $this->orderModifier->addToOrder($cart, $cartItem);

        return $productVariant->getId();
    }

    private function getVariant(array $options, ProductInterface $product): ProductVariantInterface
    {
        foreach ($product->getVariants() as $variant) {
            if ($this->areOptionsMatched($options, $variant)) {
                Assert::isInstanceOf($variant, ProductVariantInterface::class);

                return $variant;
            }
        }

        throw new \InvalidArgumentException('Variant could not be resolved');
    }

    private function areOptionsMatched(array $options, ProductVariantInterface $variant): bool
    {
        foreach ($variant->getOptionValues() as $optionValue) {
            if (!isset($options[$optionValue->getOptionCode()]) || $optionValue->getCode() !== $options[$optionValue->getOptionCode()]) {
                return false;
            }
        }

        return true;
    }
}
