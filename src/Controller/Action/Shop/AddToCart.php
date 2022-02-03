<?php

declare(strict_types=1);

namespace App\Controller\Action\Shop;

use App\Entity\Product\ProductInterface;
use App\Request\Cart\PutItemToCart\PutItemToCartCommandProvider;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;

final class AddToCart
{
    use HandleTrait;

    private ProductRepositoryInterface $productRepository;
    private RouterInterface $router;
    private PutItemToCartCommandProvider $cartCommandProvider;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        RouterInterface $router,
        MessageBusInterface $messageBus,
        PutItemToCartCommandProvider $cartCommandProvider
    ) {
        $this->productRepository = $productRepository;
        $this->router = $router;
        $this->cartCommandProvider = $cartCommandProvider;
        $this->messageBus = $messageBus;
    }

    public function __invoke(
        Request $request
    ) {
        $productId = $request->query->get('productId');

        $this->cartCommandProvider->validate($request);
        $command = $this->cartCommandProvider->getCommand($request);
        $variantId = $this->handle($command);
        /** @var ProductInterface $product */
        $product = $this->productRepository->find($productId);

        if ($product->hasRefundCodes()) {
            return new RedirectResponse($this->router->generate('app_shop_add_to_cart_with_refund',
                [
                    'productCode' => $product->getCode(),
                    'productVariantId' => $variantId
                ]));
        }

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
