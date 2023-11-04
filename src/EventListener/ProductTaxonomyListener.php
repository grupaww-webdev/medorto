<?php

namespace App\EventListener;

use App\Entity\Channel\ChannelPricing;
use App\Entity\Product\Product;
use App\Entity\Product\ProductTaxon;
use App\Entity\Taxonomy\Taxon;
use Behat\Behat\Context\Context;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Sylius\Behat\Context\Setup\ProductTaxonContext;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Taxonomy\Model\TaxonInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ProductTaxonomyListener
{
    /**
     * @var \Doctrine\ORM\EntityManager|\Doctrine\ORM\EntityManagerInterface
     */
    private $manager;
    private FactoryInterface $productTaxonFactory;
    /**
     * @var false
     */
    private bool $flush;
    private ArrayCollection $collection;


    public function __construct(
        FactoryInterface $productTaxonFactory
    ) {

        $this->productTaxonFactory = $productTaxonFactory;
        $this->flush = false;
        $this->collection = new ArrayCollection();
    }

    public function postFlush(PostFlushEventArgs $args)
    {
        $this->manager = $args->getEntityManager();

        $uof     =  $this->manager->getUnitOfWork();
        $updatedCollections = $uof->getScheduledCollectionUpdates();

        foreach ($updatedCollections as $collection) {
            foreach ($collection as $entity) {
                if($entity instanceof ProductTaxon)
                    $this->fixTaxon($entity->getTaxon(),$entity->getProduct());
            }
        }

        if($this->collection->count())
        {
            foreach ($this->collection as $entity)
                $this->manager->persist($entity);

            $this->manager->flush();
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
            $productTaxon = $this->createProductTaxon($getTaxon,$getProduct);
            $this->collection->add($productTaxon);
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
     */
    private function createProductTaxon(\Sylius\Component\Core\Model\TaxonInterface $taxon, ProductInterface $product): ProductTaxonInterface
    {
        /** @var ProductTaxonInterface $productTaxon */
        $productTaxon = $this->productTaxonFactory->createNew();
        $productTaxon->setProduct($product);
        $productTaxon->setTaxon($taxon);

        return $productTaxon;
    }
}
