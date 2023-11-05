<?php

namespace App\Console;

use App\Entity\Product\Product;
use App\Entity\Product\ProductTaxon;
use App\Entity\Taxonomy\Taxon;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\ORMException;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Core\Model\ProductInterface;
use Sylius\Component\Core\Model\ProductTaxonInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use SyliusLabs\Polyfill\Symfony\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\RouterInterface;

class FixProductTaxonCommand extends ContainerAwareCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static $defaultName = 'medorto:fix';
    private ProductRepositoryInterface $productRepository;
    private RouterInterface $router;
    private CacheManager $cache;
    private AvailabilityCheckerInterface $availabilityChecker;
    private ParameterBagInterface $parameterBag;
    private EntityManagerInterface $entityManager;
    private FactoryInterface $productTaxonFactory;
    private \Doctrine\ORM\UnitOfWork $uof;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        RouterInterface $router,
        CacheManager $cache,
        AvailabilityCheckerInterface $availabilityChecker,
        ParameterBagInterface $parameterBag,
        EntityManagerInterface $entityManager,
        FactoryInterface $productTaxonFactory
    ) {
        parent::__construct();
        $this->productRepository = $productRepository;
        $this->router = $router;
        $this->cache = $cache;
        $this->availabilityChecker = $availabilityChecker;
        $this->parameterBag = $parameterBag;
        $this->entityManager = $entityManager;
        $this->productTaxonFactory = $productTaxonFactory;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName(static::$defaultName)
            ->setDescription('Create google feed file.');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->uof     =  $this->entityManager->getUnitOfWork();

        /** @var Product $product */
        foreach ($this->productRepository->findAll() as $product) {
            foreach ($product->getProductTaxons() as $productTaxon) {
                $this->fixTaxon($productTaxon->getTaxon(), $productTaxon->getProduct());
            }
        }
        $this->entityManager->flush();
        return 0;
    }

    private function fixTaxon(
        ?Taxon $getTaxon,
        ?Product $getProduct
    ) {
        if (null === $getTaxon || null === $getProduct) {
            return;
        }

        $check = $this->entityManager->getRepository(ProductTaxon::class)->findOneBy([
            'product' => $getProduct,
            'taxon' => $getTaxon
        ]);

        if (null === $check) {
            if($this->isEntityNotScheduled($getTaxon,$getProduct)){
                $this->createProductTaxon($getTaxon,$getProduct);
            }
        }

        if ($getTaxon->getParent()) {
            $this->fixTaxon($getTaxon->getParent(), $getProduct);
        }
    }

    /**
     * @param  TaxonInterface  $taxon
     * @param  ProductInterface  $product
     *
     * @return ProductTaxonInterface
     */
    private function createProductTaxon(TaxonInterface $taxon, ProductInterface $product): ProductTaxonInterface
    {
        /** @var ProductTaxonInterface $productTaxon */
        $productTaxon = $this->productTaxonFactory->createNew();
        $productTaxon->setProduct($product);
        $productTaxon->setTaxon($taxon);

        $this->entityManager->persist($productTaxon);

        $class = $this->entityManager->getClassMetadata(get_class($productTaxon));
        $this->uof->computeChangeSet($class, $productTaxon);

        return $productTaxon;
    }

    private function isEntityNotScheduled(Taxon $taxon, Product $product): bool
    {
        foreach ($this->uof->getScheduledEntityInsertions() as $entityInsertion)
            if($entityInsertion instanceof ProductTaxon)
                if($entityInsertion->getTaxon()->getId() === $taxon->getId())
                    if($entityInsertion->getProduct()->getId() === $product->getId())
                        return false;

        return true;
    }
}
