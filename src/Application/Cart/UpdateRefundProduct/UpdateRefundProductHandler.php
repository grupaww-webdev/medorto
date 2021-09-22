<?php

declare(strict_types=1);

namespace App\Application\Cart\UpdateRefundProduct;

use App\Calculator\ProductVariantPriceCalculator;
use App\Entity\Order\OrderItem;
use App\Entity\Product\Product;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateRefundProductHandler implements MessageHandlerInterface
{
    /**
     * @var CartContextInterface
     */
    private $cartContext;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var ProductVariantPriceCalculator
     */
    private $productVariantPricesCalculator;

    public function __construct(
        CartContextInterface $cartContext,
        ProductRepositoryInterface $productRepository,
        EntityManagerInterface $entityManager,
        ProductVariantPriceCalculator $productVariantPricesCalculator
    ) {
        $this->cartContext = $cartContext;
        $this->productRepository = $productRepository;
        $this->entityManager = $entityManager;
        $this->productVariantPricesCalculator = $productVariantPricesCalculator;
    }

    public function __invoke(UpdateRefundProductCommand $command): void
    {
        /** @var Product $product */
        $product = $this->productRepository->find($command->getProductId());
        $refundCode = $product->getRefundCode($command->getRefundCode());
        /** @var OrderItem $item */
        foreach ($this->cartContext->getCart()->getItems() as $item) {
            if ($command->getProductVariantId() === $item->getVariant()->getId()) {
                $item->setRefund($refundCode);
                $item->setUnitPrice($this->productVariantPricesCalculator->calculate(
                    $item->getVariant(), [
                        'code' => $command->getRefundCode(),
                        'channel' => $this->cartContext->getCart()->getChannel()
                    ]
                ));

                $this->cartContext->getCart()->recalculateItemsTotal();
                $this->entityManager->flush();
            }
        }
    }
}
