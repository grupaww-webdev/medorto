<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Shipping\PickupPoint;
use Sylius\Bundle\ResourceBundle\Controller\ResourceController;
use Sylius\Component\Resource\ResourceActions;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class PickupPointController extends ResourceController
{
    public function activeAction(string $pointId, Request $request)
    {
        /** @var PickupPoint $pickupPoint */
        $pickupPoint = $this->repository->find($pointId);

        if ($pickupPoint->isActive()) {
            $pickupPoint->deactivate();
        } else {
            $pickupPoint->activate();
        }
        $configuration = $this->requestConfigurationFactory->create($this->metadata, $request);
        $this->resourceUpdateHandler->handle($pickupPoint, $configuration, $this->manager);

        if ($configuration->isHtmlRequest()) {
            $this->flashHelper->addSuccessFlash($configuration, ResourceActions::UPDATE, $pickupPoint);
        }

        $postEvent = $this->eventDispatcher->dispatchPostEvent(ResourceActions::UPDATE, $configuration, $pickupPoint);

        if (!$configuration->isHtmlRequest()) {
            if ($configuration->getParameters()->get('return_content', false)) {
                return $this->createRestView($configuration, $pickupPoint, Response::HTTP_OK);
            }

            return $this->createRestView($configuration, null, Response::HTTP_NO_CONTENT);
        }

        $postEventResponse = $postEvent->getResponse();
        if (null !== $postEventResponse) {
            return $postEventResponse;
        }

        return $this->redirectHandler->redirectToResource($configuration, $pickupPoint);
    }
}
