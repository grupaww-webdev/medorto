<?php

namespace App\EventListener;

use App\Entity\Channel\ChannelPricing;
use App\Entity\Product\Product;
use App\Entity\Product\ProductTaxon;
use App\Entity\Taxonomy\Taxon;
use Behat\Behat\Context\Context;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\OnFlushEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\ORMException;
use Sylius\Behat\Context\Setup\ProductTaxonContext;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ProductTaxonomyListener
{
    private $manager;
    private FactoryInterface $productTaxonFactory;
    private \Doctrine\ORM\UnitOfWork $uof;


    public function __construct(
        FactoryInterface $productTaxonFactory
    ) {
        $this->productTaxonFactory = $productTaxonFactory;
    }

    public function onFlush(OnFlushEventArgs $args): bool
    {
        $this->manager = $args->getEntityManager();

        $this->uof     =  $this->manager->getUnitOfWork();

        foreach ($this->uof->getScheduledEntityInsertions() as $entity) {
            if($entity instanceof ProductTaxon)
                $this->fixTaxon($entity->getTaxon(),$entity->getProduct());
        }

        return true;
    }

    private function fixTaxon(
        ?Taxon $getTaxon,
        ?Product $getProduct
    ) {
        if (null === $getTaxon || null === $getProduct) {
            return;
        }

        $check = $this->manager->getRepository(ProductTaxon::class)->findOneBy([
            'product' => $getProduct,
            'taxon' => $getTaxon
        ]);

        if (null === $check) {
            if($this->isEntityNotScheduled($getTaxon)){
                $this->createProductTaxon($getTaxon,$getProduct);
            }
        }

        if ($getTaxon->getParent()) {
            $this->fixTaxon($getTaxon->getParent(), $getProduct);
        }
    }

    /**
     * @param  \Sylius\Component\Core\Model\TaxonInterface  $taxon
     * @param  ProductInterface  $product
     *
     * @return ProductTaxonInterface
     * @throws ORMException
     */
    private function createProductTaxon(\Sylius\Component\Core\Model\TaxonInterface $taxon, ProductInterface $product): ProductTaxonInterface
    {
        /** @var ProductTaxonInterface $productTaxon */
        $productTaxon = $this->productTaxonFactory->createNew();
        $productTaxon->setProduct($product);
        $productTaxon->setTaxon($taxon);

        $this->manager->persist($productTaxon);

        $class = $this->manager->getClassMetadata(get_class($productTaxon));
        $this->uof->computeChangeSet($class, $productTaxon);

        return $productTaxon;
    }

    private function isEntityNotScheduled(Taxon $productTaxon): bool
    {
        foreach ($this->uof->getScheduledEntityInsertions() as $entityInsertion)
            if($entityInsertion instanceof ProductTaxon)
                if($entityInsertion->getTaxon()->getId() === $productTaxon->getId())
                    return false;

        return true;
    }
}
