<?php

declare(strict_types=1);

namespace App\PickupPoint\Provider;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;

final class LocalPickupPointProvider implements PickupPointProviderInterface
{
    public const
        NAME = 'Local',
        CODE = 'local';
    /**
     * @var RepositoryInterface
     */
    private $pickupPointRepository;

    public function __construct(RepositoryInterface $pickupPointRepository)
    {
        $this->pickupPointRepository = $pickupPointRepository;
    }

    public function getCode(): string
    {
        return self::CODE;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function findPickupPoints(OrderInterface $order): array
    {
        return $this->pickupPointRepository->findBy(['active' => true]);
    }
}
