<?php

declare(strict_types=1);


namespace App\Importer\Exception;

final class ImageNotFoundException extends \Exception
{
    public function __construct()
    {
        parent::__construct('Image not found');
    }
}
