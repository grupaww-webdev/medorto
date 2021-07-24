<?php

declare(strict_types=1);

namespace App\PickupPoint\Manager;

use App\PickupPoint\Exception\NonUniqueProviderCodeException;
use App\PickupPoint\Provider\PickupPointProviderInterface;

final class ProviderManager implements ProviderManagerInterface
{
    /** @var PickupPointProviderInterface[] */
    private $providers;

    public function __construct(iterable $pickupPointProviders)
    {
        $this->providers = [];
        foreach ($pickupPointProviders as $provider) {
            $this->addProvider($provider);
        }
    }

    public function all(): array
    {
        return $this->providers;
    }

    public function addProvider(PickupPointProviderInterface $pickupPointProvider): void
    {
        if (null !== $this->findByCode($pickupPointProvider->getCode())) {
            throw new NonUniqueProviderCodeException($pickupPointProvider);
        }

        $this->providers[] = $pickupPointProvider;
    }

    public function findByCode(string $code): ?PickupPointProviderInterface
    {
        foreach ($this->providers as $provider) {
            if ($code === $provider->getCode()) {
                return $provider;
            }
        }

        return null;
    }

    public function findByClassName(string $class): ?PickupPointProviderInterface
    {
        foreach($this->providers as $provider) {
            if (get_class($provider) === $class) {
                return $provider;
            }
        }

        return null;
    }
}
