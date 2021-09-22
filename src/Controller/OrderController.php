<?php

declare(strict_types=1);

namespace App\Controller;

use Sylius\Bundle\CoreBundle\Controller\OrderController as BaseOrderController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class OrderController extends BaseOrderController
{
    public function thankYouAction(Request $request): Response
    {
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $orderId = $request->getSession()->get('sylius_order_id', null);

        if (null === $orderId) {
            $options = $configuration->getParameters()->get('after_failure');

            return $this->redirectHandler->redirectToRoute(
                $configuration,
                $options['route'] ?? 'sylius_shop_homepage',
                $options['parameters'] ?? []
            );
        }
        $order = $this->repository->find($orderId);
        Assert::notNull($order);

        return $this->render(
            $configuration->getParameters()->get('template'),
            [
                'order' => $order,
            ]
        );
    }
}
