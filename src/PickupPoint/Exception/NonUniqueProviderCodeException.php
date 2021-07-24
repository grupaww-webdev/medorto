<?php

declare(strict_types=1);

namespace App\PickupPoint\Exception;

use App\PickupPoint\Provider\PickupPointProviderInterface;
use InvalidArgumentException;
use Throwable;

final class NonUniqueProviderCodeException extends InvalidArgumentException
{
    public function __construct(PickupPointProviderInterface $provider, int $code = 0, Throwable $previous = null)
    {
        parent::__construct("The code '".$provider->getCode()."' is not unique. Found in ".get_class($provider), $code,
            $previous);
    }
}
