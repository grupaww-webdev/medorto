<?php

declare(strict_types=1);

namespace App\Controller\Action\Shop;

use App\Application\Cart\UpdateRefundProduct\UpdateRefundProductCommand;
use App\Entity\Product\Product;
use Exception;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class UpdateRefundProduct
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
     * @var RouterInterface
     */
    private $router;
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        ChannelContextInterface $channelContext,
        SessionInterface $session,
        TranslatorInterface $translator,
        RouterInterface $router,
        MessageBusInterface $messageBus
    ) {
        $this->productRepository = $productRepository;
        $this->channelContext = $channelContext;
        $this->session = $session;
        $this->translator = $translator;
        $this->router = $router;
        $this->messageBus = $messageBus;
    }

    public function __invoke(Request $request, string $productCode, int $productVariantId)
    {
        try {
            $channel = $this->channelContext->getChannel();

            /** @var Product $product */
            $product = $this->productRepository->findOneByChannelAndCode($channel, $productCode);
            if (null === $product) {
                throw new Exception($this->translator->trans('sylius.exception.product_not_found'));
            }

            if (false === $product->hasRefundCodes()) {
                throw new Exception($this->translator->trans('sylius.exception.no_product_refund'));
            }
            $this->messageBus->dispatch(
                new UpdateRefundProductCommand(
                    $product->getId(),
                    $productVariantId,
                    $request->request->get('refund-code')
                )
            );
        } catch (Exception $exception) {
            $this->session->getFlashBag()->add('danger', $exception->getMessage());
        }
        return new RedirectResponse($this->router->generate('sylius_shop_cart_summary'));
    }
}
