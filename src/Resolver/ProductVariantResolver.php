<?php

declare(strict_types=1);

namespace App\Resolver;

use App\Entity\Product\Product;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Model\ProductOptionValueInterface;
use Sylius\Component\Product\Repository\ProductOptionRepositoryInterface;
use Sylius\Component\Product\Resolver\AvailableProductOptionValuesResolverInterface;
use Sylius\Component\Product\Resolver\ProductVariantResolverInterface;
use Symfony\Component\HttpFoundation\RequestStack;

final class ProductVariantResolver
{
    /**
     * @var RequestStack
     */
    private $requestStack;
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    public function __construct(
        RequestStack $requestStack,
        ProductRepositoryInterface $productRepository
    ) {
        $this->requestStack = $requestStack;
        $this->productRepository = $productRepository;
    }

    public function resolve(int $productId)
    {
        $request = $this->requestStack->getCurrentRequest();

        if (null === $request) {
            throw new \Exception('Missing request');
        }

        $addToCart = $request->request->get('sylius_add_to_cart');

        if (false === isset($addToCart['cartItem'])) {
            throw new \Exception('Missing cart item');
        }
        /** @var Product $product */
        $product = $this->productRepository->find($productId);

        if (null === $product) {
            throw new \Exception('Missing product');
        }

        $variant = $addToCart['cartItem']['variant'] ?? [];

        foreach ($product->getVariants() as $productVariant) {
            /** @var ProductOptionValueInterface $variantProductOptionValue */
            foreach ($productVariant->getOptionValues() as $variantProductOptionValue) {
                if (
                    $variantProductOptionValue->getCode() === $variant[$variantProductOptionValue->getOption()->getCode()]
                ) {
                    return $productVariant;
                }
            }
        }
        throw new \Exception('Missing variant');
    }
}
