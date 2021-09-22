<?php

declare(strict_types=1);

namespace App\Controller\Action\Shop;

use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Core\Repository\ShippingMethodRepositoryInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Sylius\Component\Registry\ServiceRegistryInterface;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

final class ShipmentMethodList
{
    /**
     * @var ShippingMethodRepositoryInterface
     */
    private $shippingMethodRepository;
    /**
     * @var ChannelContextInterface
     */
    private $channelContext;
    /**
     * @var Environment
     */
    private $template;
    /**
     * @var LocaleContextInterface
     */
    private $localeContext;

    public function __construct(
        ShippingMethodRepositoryInterface $shippingMethodRepository,
        ChannelContextInterface $channelContext,
        LocaleContextInterface $localeContext,
        Environment $template
    ) {
        $this->shippingMethodRepository = $shippingMethodRepository;
        $this->channelContext = $channelContext;
        $this->template = $template;
        $this->localeContext = $localeContext;
    }

    public function __invoke(): Response
    {
        $rawShippingMethods = [];
        $channel = $this->channelContext->getChannel();
        $locale = $this->localeContext->getLocaleCode();
        $shippingMethods = $this->shippingMethodRepository->findEnabledForChannel($channel);
        foreach ($shippingMethods as $shippingMethod) {
            $rawShippingMethods[] = [
                'id' => $shippingMethod->getId(),
                'code' => $shippingMethod->getCode(),
                'name' => $shippingMethod->getTranslation($locale)->getName(),
                'description' => $shippingMethod->getTranslation($locale)->getDescription(),
                'price' => $shippingMethod->getConfiguration()[$channel->getCode()]
            ];
        }

        return new Response($this->template->render(
            'App/Shop/Product/Show/Shipment/_shipping_method.html.twig',
            [
                'shippingMethods' => $rawShippingMethods
            ]
        ));
    }
}
