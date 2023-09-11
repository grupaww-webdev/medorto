<?php

declare(strict_types=1);

namespace App\Controller\Action\Shop;

use App\Application\Cart\UpdateRefundProduct\UpdateRefundProductCommand;
use App\Entity\Product\Product;
use App\Entity\Product\ProductInterface;
use App\Request\Cart\PutItemToCart\PutItemToCartCommandProvider;
use App\Validation\Exception\ValidationException;
use Exception;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
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
        $refundCode = $request->request->get('refund-code');
        $isRefundBuy = $request->request->get('tabset');

        /** @var ProductInterface $product */
        $product = $this->productRepository->find($productId);

        try {
            $this->cartCommandProvider->validate($request);

            $command = $this->cartCommandProvider->getCommand($request);
            $variantId = $this->handle($command);

            if ($product->hasRefundCodes()) {
                if ($isRefundBuy == 'refund' && null !== $refundCode) {
                    $this->refund($request, $product, $variantId, $refundCode);
                }
            }
        } catch (ValidationException $validationException) {
            $request->getSession()->getFlashBag()->add('cart-error', $validationException->getViolationList()->get(0)->getMessage());
            return new RedirectResponse($this->router->generate('sylius_shop_product_show', ['_locale' => $request->getLocale(), 'slug' => $product->getSlug()]));
        } catch (HandlerFailedException $exception) {
            $request->getSession()->getFlashBag()->add('cart-error', $exception->getNestedExceptions()[0]->getMessage());
            return new RedirectResponse($this->router->generate('sylius_shop_product_show', ['_locale' => $request->getLocale(), 'slug' => $product->getSlug()]));
        } catch (Exception $exception) {
            $request->getSession()->getFlashBag()->add('cart-error', $exception->getMessage());
            return new RedirectResponse($this->router->generate('sylius_shop_product_show', ['_locale' => $request->getLocale(), 'slug' => $product->getSlug()]));
        }


        $request->getSession()->getFlashBag()->add('cart', 'add.product' );

        return new RedirectResponse($this->router->generate('sylius_shop_product_show',['_locale' => $request->getLocale(), 'slug' => $product->getSlug()]));
    }

    public function refund(Request $request,ProductInterface $product, int $productVariantId, string $refundCode): RedirectResponse
    {
        try {
            $this->messageBus->dispatch(
                new UpdateRefundProductCommand(
                    $product->getId(),
                    $productVariantId,
                    $refundCode
                )
            );
        } catch (Exception $exception) {
            $request->getSession()->getFlashBag()->add('danger', $exception->getMessage());
        }
        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
