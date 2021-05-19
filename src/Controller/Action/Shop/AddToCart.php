<?php

declare(strict_types=1);

namespace App\Controller\Action\Shop;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Factory\CartItemFactoryInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Order\Modifier\OrderItemQuantityModifierInterface;
use Sylius\Component\Order\Processor\OrderProcessorInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;

final class AddToCart
{
    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var CartItemFactoryInterface */
    private $orderItemFactory;

    /** @var OrderItemQuantityModifierInterface */
    private $orderItemQuantityModifier;

    /** @var CartContextInterface */
    private $cartContext;

    /** @var OrderProcessorInterface */
    private $orderProcessor;

    /** @var RouterInterface */
    private $router;

    /** @var SessionInterface */
    private $session;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        CartItemFactoryInterface $orderItemFactory,
        OrderItemQuantityModifierInterface $orderItemQuantityModifier,
        CartContextInterface $cartContext,
        OrderProcessorInterface $orderProcessor,
        RouterInterface $router,
        SessionInterface $session,
        EntityManagerInterface $em
    ) {
        $this->productRepository = $productRepository;
        $this->orderItemFactory = $orderItemFactory;
        $this->orderItemQuantityModifier = $orderItemQuantityModifier;
        $this->cartContext = $cartContext;
        $this->orderProcessor = $orderProcessor;
        $this->router = $router;
        $this->session = $session;
        $this->em = $em;
    }

    public function __invoke(
        Request $request
    ) {
        $productId = $request->query->get('productId');
        $quantity = $request->query->get('quantity', 1);
        /** @var ProductInterface $product */
        $product = $this->productRepository->find($productId);

        $orderItem = $this->orderItemFactory->createForProduct($product);
        $this->orderItemQuantityModifier->modify($orderItem, (int) $quantity);
        $cart = $this->cartContext->getCart();
        $cart->addItem($orderItem);
        $this->orderProcessor->process($cart);
        $this->em->flush();
        $this->session->getBag('flashes')->add('success', 'sylius.cart.add_item');

        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
