<?php

declare(strict_types=1);

namespace App\Importer;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface ImageExternalImporterInterface
{
    public function importFromUrl(string $url): UploadedFile;
}
