<?php

declare(strict_types=1);

namespace App\Request;

use Symfony\Component\HttpFoundation\Request;

interface CommandProviderInterface
{
    public function validate(Request $httpRequest, array $constraints = null, array $groups = null) : void;
    public function getCommand(Request $httpRequest) : object;
}
