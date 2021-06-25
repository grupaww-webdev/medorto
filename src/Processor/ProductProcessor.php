<?php

declare(strict_types=1);

namespace App\Processor;

use App\Importer\ImageExternalImporterInterface;
use FriendsOfSylius\SyliusImportExportPlugin\Processor\ResourceProcessorInterface;
use FriendsOfSylius\SyliusImportExportPlugin\Processor\ProductProcessor as BaseProductProcessor;
use FriendsOfSylius\SyliusImportExportPlugin\Service\ImageTypesProvider;
use FriendsOfSylius\SyliusImportExportPlugin\Service\ImageTypesProviderInterface;
use Sylius\Component\Core\Model\ProductImageInterface;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Repository\ProductRepositoryInterface;
use Sylius\Component\Core\Uploader\ImageUploaderInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

final class ProductProcessor implements ResourceProcessorInterface
{
    /** @var BaseProductProcessor */
    private $productProcessor;

    /** @var ProductRepositoryInterface */
    private $productRepository;

    /** @var ImageTypesProviderInterface */
    private $imageTypesProvider;

    /** @var FactoryInterface */
    private $productImageFactory;

    /** @var ImageExternalImporterInterface */
    private $imageExternalImporter;

    /** @var ImageUploaderInterface */
    private $imageUploader;

    /** @var array */
    private $headerKeys;

    public function __construct(
        BaseProductProcessor $productProcessor,
        ProductRepositoryInterface $productRepository,
        ImageTypesProviderInterface $imageTypesProvider,
        FactoryInterface $productImageFactory,
        ImageExternalImporterInterface $imageExternalImporter,
        ImageUploaderInterface $imageUploader,
        array $headerKeys
    ) {
        $this->productProcessor = $productProcessor;
        $this->productRepository = $productRepository;
        $this->imageTypesProvider = $imageTypesProvider;
        $this->productImageFactory = $productImageFactory;
        $this->imageExternalImporter = $imageExternalImporter;
        $this->imageUploader = $imageUploader;
        $this->headerKeys = $headerKeys;
    }

    public function process(array $data): void
    {
        $this->productProcessor->process($data);
        $product = $this->productRepository->findOneBy(['code' => $data['Code']]);
        $this->setImage($product, $data);
    }

    private function setImage(ProductInterface $product, array $data): void
    {
        $productImageCodes = $this->imageTypesProvider->getProductImagesCodesList();
        foreach ($productImageCodes as $imageType) {
            /** @var ProductImageInterface $productImage */
            $productImageByType = $product->getImagesByType($imageType);

            // remove old images if import is empty
            foreach ($productImageByType as $productImage) {
                if (empty($data[ImageTypesProvider::IMAGES_PREFIX . $imageType])) {
                    if ($productImage !== null) {
                        $product->removeImage($productImage);
                    }

                    continue;
                }
            }

            if (empty($data[ImageTypesProvider::IMAGES_PREFIX . $imageType])) {
                continue;
            }

            if (count($productImageByType) === 0) {
                /** @var ProductImageInterface $productImage */
                $productImage = $this->productImageFactory->createNew();
            } else {
                $productImage = $productImageByType->first();
            }
            $uploadedFile = $this->imageExternalImporter->importFromUrl($data[ImageTypesProvider::IMAGES_PREFIX . $imageType]);

            $productImage->setType($imageType);
            $productImage->setFile($uploadedFile);
            $this->imageUploader->upload($productImage);

            $product->addImage($productImage);
        }

        // create image if import has new one
        foreach ($this->imageTypesProvider->extractImageTypeFromImport(\array_keys($data)) as $imageType) {
            if (\in_array($imageType, $productImageCodes) || empty($data[ImageTypesProvider::IMAGES_PREFIX . $imageType])) {
                continue;
            }

            /** @var ProductImageInterface $productImage */
            $productImage = $this->productImageFactory->createNew();
            $productImage->setType($imageType);
            $productImage->setPath($data[ImageTypesProvider::IMAGES_PREFIX . $imageType]);
            $product->addImage($productImage);
        }
    }

}
