<?php

declare(strict_types=1);


namespace App\Request\Cart\PutItemToCart;

use App\Request\CommandProviderInterface;
use App\Validation\Exception\ValidationException;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Order\Context\CartContextInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class PutItemToCartCommandProvider implements CommandProviderInterface
{
    /** @var ValidatorInterface */
    private $validator;
    /** @var CartContextInterface */
    private $cartContext;
    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(
        ValidatorInterface $validator,
        CartContextInterface $cartContext,
        EntityManagerInterface $entityManager
    ) {
        $this->validator = $validator;
        $this->cartContext = $cartContext;
        $this->entityManager = $entityManager;
    }

    public function validate(Request $httpRequest, array $constraints = null, array $groups = null) : void
    {
        $exceptions = $this->validator->validate($this->transformHttpRequest($httpRequest));
        if (0 === $exceptions->count()) {
            return;
        }

        throw new ValidationException($exceptions);
    }

    public function getCommand(Request $httpRequest): object
    {
        return $this->transformHttpRequest($httpRequest)->getCommand();
    }

    private function transformHttpRequest(Request $httpRequest) : RequestInterface
    {
        $hasVariantCode = $this->hasVariant($httpRequest);
        $hasOptionCode = $this->hasOption($httpRequest);

        $cart = $this->getCart();


        if (!$hasVariantCode && !$hasOptionCode) {
            return PutSimpleItemToCartRequest::fromHttpRequest(
                $cart->getId(),
                $httpRequest
            );
        }

        if ($hasVariantCode && !$hasOptionCode) {
            return PutVariantBasedConfigurableItemToCartRequest::fromHttpRequest(
                $cart->getId(),
                $httpRequest
            );
        }

        if (!$hasVariantCode && $hasOptionCode) {
            return PutOptionBasedConfigurableItemToCartRequest::fromHttpRequest(
                $cart->getId(),
                $httpRequest
            );
        }

        throw new NotFoundHttpException('Variant not found for given configuration');
    }

    private function hasVariant(Request $httpRequest) : bool
    {
        if ($httpRequest->request->has('variantCode')) {
            return true;
        }

        if (false === $httpRequest->request->has('sylius_add_to_cart')) {
            return false;
        }

        $addToCart = $httpRequest->request->get('sylius_add_to_cart');

        if (false === isset($addToCart['cartItem'])) {
            return false;
        }
        $cartItem = $addToCart['cartItem'];

        if (false === isset($cartItem['variant'])) {
            return false;
        }

        return false === \is_array($cartItem['variant']);
    }

    private function getCart() : OrderInterface
    {
        /** @var OrderInterface $cart */
        $cart = $this->cartContext->getCart();

        if (null === $cart->getId()) {
            $this->entityManager->persist($cart);
            $this->entityManager->flush();
        }

        return $cart;
    }

    private function hasOption(Request $httpRequest) : bool
    {
        if ($httpRequest->request->has('options')) {
            return true;
        }

        if (false === $httpRequest->request->has('sylius_add_to_cart')) {
            return false;
        }

        $addToCart = $httpRequest->request->get('sylius_add_to_cart');

        if (false === isset($addToCart['cartItem'])) {
            return false;
        }
        $cartItem = $addToCart['cartItem'];


        if (false === isset($cartItem['variant'])) {
            return false;
        }

        return \is_array($cartItem['variant']);
    }
}
