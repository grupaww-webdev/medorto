<?php

declare(strict_types=1);

namespace App\Importer;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageExternalImporter implements ImageExternalImporterInterface
{
    /** @var string */
    private $dir;

    public function __construct(string $dir)
    {
        $this->dir = $dir;
    }

    public function importFromUrl(string $url): UploadedFile
    {
        $filePaths = explode('/', $url);
        $fieldName = $this->dir . end($filePaths);

        if (file_exists($fieldName)) {
            unlink($fieldName);
        }

        if (!file_exists($this->dir)) {
            mkdir($this->dir, 0777, true);
        }

        copy($url, $fieldName);

        return new UploadedFile($fieldName, end($filePaths));
    }
}
