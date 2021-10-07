<?php

declare(strict_types=1);

namespace App\Importer;

use App\Importer\Exception\ImageNotFoundException;
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

        $arrContextOptions = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ],
        ];
        $imageInfo = @getimagesize($url);
        if (null === $imageInfo) {
            throw new ImageNotFoundException();
        }
        $response = file_get_contents($url, false, stream_context_create($arrContextOptions));
        file_put_contents($fieldName, $response);

        return new UploadedFile($fieldName, end($filePaths));
    }
}
