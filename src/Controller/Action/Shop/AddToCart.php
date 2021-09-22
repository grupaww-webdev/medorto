<?php

declare(strict_types=1);

namespace App\Controller\Action\Shop;

use App\Application\Cart\PutSimpleItemToCart\PutSimpleItemToCartCommand;
use App\Entity\Product\ProductInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;

final class AddToCart
{
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var CartContextInterface */
    private $cartContext;

    /** @var RouterInterface */
    private $router;

    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CartContextInterface $cartContext,
        RouterInterface $router,
        EntityManagerInterface $entityManager,
        MessageBusInterface $messageBus
    ) {
        $this->productRepository = $productRepository;
        $this->cartContext = $cartContext;
        $this->router = $router;
        $this->messageBus = $messageBus;
        $this->entityManager = $entityManager;
    }

    public function __invoke(
        Request $request
    ) {
        $productId = $request->query->get('productId');
        if ($request->isMethod('GET')) {
            $quantity = $request->query->get('quantity', 1);
        } else {
            $quantity = $request->request->get('sylius_add_to_cart')['cartItem']['quantity'];
        }

        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        if (null === $cart->getId()) {
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        $this->messageBus->dispatch(
            new PutSimpleItemToCartCommand(
                $cart->getId(),
                (int) $productId,
                (int) $quantity
            )
        );

        /** @var ProductInterface $product */
        $product = $this->productRepository->find($productId);

        if ($product->hasRefundCodes()) {
            return new RedirectResponse($this->router->generate('app_shop_add_to_cart_with_refund',
                [
                    'productCode' => $product->getCode(),
                    'productVariantId' => $product->getVariants()[0]->getId()
                ]));
        }

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
