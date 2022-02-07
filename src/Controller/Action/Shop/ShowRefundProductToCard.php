<?php

declare(strict_types=1);

namespace App\Controller\Action\Shop;

use App\Entity\Product\Product;
use Exception;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Product\Repository\ProductVariantRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class ShowRefundProductToCard
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;
    /**
     * @var SessionInterface
     */
    private $session;
    /**
     * @var TranslatorInterface
     */
    private $translator;
    /**
     * @var Environment
     */
    private $template;
    /**
     * @var RouterInterface
     */
    private $router;
    private ProductVariantRepositoryInterface $productVariantRepository;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ProductVariantRepositoryInterface $productVariantRepository,
        ChannelContextInterface $channelContext,
        SessionInterface $session,
        TranslatorInterface $translator,
        Environment $template,
        RouterInterface $router
    ) {
        $this->productRepository = $productRepository;
        $this->channelContext = $channelContext;
        $this->session = $session;
        $this->translator = $translator;
        $this->template = $template;
        $this->router = $router;
        $this->productVariantRepository = $productVariantRepository;
    }

    public function __invoke(string $productCode, int $productVariantId, Request $request)
    {
        try {
            $channel = $this->channelContext->getChannel();

            /** @var Product $product */
            $product = $this->productRepository->findOneByChannelAndCode($channel, $productCode);
            $productVariant = $this->productVariantRepository->find($productVariantId);

            if (null === $product) {
                throw new Exception($this->translator->trans('sylius.exception.product_not_found'));
            }

            if (false === $product->hasRefundCodes()) {
                throw new Exception($this->translator->trans('sylius.exception.no_product_refund'));
            }
        } catch (Exception $exception) {
            $this->session->getFlashBag()->add('danger', $exception->getMessage());
            return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
        }

        return new Response($this->template->render(
            'App/Shop/Cart/add_to_cart_refund.html.twig',
            [
                'product' => $product,
                'variant' => $productVariant
            ]
        ));
    }
}
