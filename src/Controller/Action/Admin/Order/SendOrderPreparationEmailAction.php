<?php

declare(strict_types=1);

namespace App\Controller\Action\Admin\Order;

use App\EmailManager\OrderNotificationEmailManager;
use Sylius\Bundle\AdminBundle\EmailManager\OrderEmailManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Repository\OrderRepositoryInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class SendOrderPreparationEmailAction
{
    /** @var OrderRepositoryInterface */
    private $orderRepository;

    /** @var OrderEmailManagerInterface */
    private $orderEmailManager;

    /** @var CsrfTokenManagerInterface */
    private $csrfTokenManager;

    /** @var Session */
    private $session;

    public function __construct(
        OrderRepositoryInterface $orderRepository,
        OrderNotificationEmailManager $orderEmailManager,
        CsrfTokenManagerInterface $csrfTokenManager,
        SessionInterface $session
    ) {
        $this->orderRepository = $orderRepository;
        $this->orderEmailManager = $orderEmailManager;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->session = $session;
    }

    public function __invoke(Request $request): Response
    {
        $orderId = $request->attributes->get('id');

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken($orderId, (string) $request->query->get('_csrf_token')))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        /** @var OrderInterface|null $order */
        $order = $this->orderRepository->find($orderId);
        if ($order === null) {
            throw new NotFoundHttpException(sprintf('The order with id %s has not been found', $orderId));
        }

        $this->orderEmailManager->sendPreparationEmail($order);

        $this->session->getFlashBag()->add(
            'success',
            'Email has been send.'
        );

        return new RedirectResponse($request->headers->get('referer'));
    }
}
