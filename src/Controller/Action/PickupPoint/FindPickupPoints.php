<?php

declare(strict_types=1);

namespace App\Controller\Action\PickupPoint;

use App\Entity\Shipping\PickupPointInterface;
use App\PickupPoint\Manager\ProviderManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use FOS\RestBundle\View\ViewHandlerInterface;
use FOS\RestBundle\View\View;

final class FindPickupPoints
{
    /**
     * @var ProviderManagerInterface
     */
    private $providerManager;
    /**
     * @var CartContextInterface
     */
    private $cartContext;
    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;
    /**
     * @var ViewHandlerInterface
     */
    private $viewHandler;

    public function __construct(
        ViewHandlerInterface $viewHandler,
        ProviderManagerInterface $providerManager,
        CartContextInterface $cartContext,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->providerManager = $providerManager;
        $this->cartContext = $cartContext;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->viewHandler = $viewHandler;
    }

    public function __invoke(string $providerCode, Request $request): Response
    {
        /** @var OrderInterface $order */
        $order = $this->cartContext->getCart();

//        if (!$this->isCsrfTokenValid((string) $order->getId(), $request->get('_csrf_token'))) {
//            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid CSRF token.');
//        }
        $provider = $this->providerManager->findByCode($providerCode);
        if (null === $provider) {
            throw new NotFoundHttpException();
        }
        $pickupPoints = $provider->findPickupPoints($order);
        return $this->viewHandler->handle(View::create(array_map(function(PickupPointInterface $record) {
            return $record->serialize();
        }, $pickupPoints)));
    }

    private function isCsrfTokenValid(string $id, ?string $token): bool
    {
        return $this->csrfTokenManager->isTokenValid(new CsrfToken($id, $token));
    }
}
