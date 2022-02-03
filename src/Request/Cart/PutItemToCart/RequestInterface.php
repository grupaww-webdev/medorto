<?php

declare(strict_types=1);

namespace App\Request\Cart\PutItemToCart;

use Symfony\Component\HttpFoundation\Request;

interface RequestInterface
{
    public static function fromHttpRequest(int $cartId, Request $request) : self;
    public function getCommand() : object;
}
