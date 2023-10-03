<?php

namespace App\Console;

use App\Entity\Product\ProductVariant;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Sylius\Component\Inventory\Checker\AvailabilityCheckerInterface;
use Sylius\Component\Product\Repository\ProductRepositoryInterface;
use SyliusLabs\Polyfill\Symfony\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Vitalybaev\GoogleMerchant\Feed;
use Vitalybaev\GoogleMerchant\Product;
use Vitalybaev\GoogleMerchant\Product\Shipping;
use Vitalybaev\GoogleMerchant\Product\Availability\Availability;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class CreateGoogleFeedCommand extends ContainerAwareCommand implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    protected static $defaultName = 'medorto:feed';
    private ProductRepositoryInterface $productRepository;
    private RouterInterface $router;
    private CacheManager $cache;
    private AvailabilityCheckerInterface $availabilityChecker;
    private ParameterBagInterface $parameterBag;

    public function __construct(
        ProductRepositoryInterface $productRepository,
        RouterInterface $router,
        CacheManager $cache,
        AvailabilityCheckerInterface $availabilityChecker,
        ParameterBagInterface $parameterBag
    ) {
        parent::__construct();
        $this->productRepository = $productRepository;

        // Poniżej używamy kontenera do uzyskania generatora URL
//        $urlGenerator = $this->container->get('router');
        $this->router = $router;
        $this->cache = $cache;
        $this->availabilityChecker = $availabilityChecker;
        $this->parameterBag = $parameterBag;
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
        // Create feed object
        $feed = new Feed("My awesome store", "https://medorto.pl", "My awesome description");

        /** @var \App\Entity\Product\Product $product */
        foreach ($this->productRepository->findAll() as $product) {
            foreach ($product->getVariants() as $variant) /** @var Product $item */ {
                if ($item = $this->addProduct($variant)) {
                    $feed->addProduct($item);
                }
            }
        }

        $filePath = $this->saveFeed($feed);

        $output->writeln("Plik XML został zapisany w: $filePath");

        return 0;
    }

    protected function addProduct(ProductVariant $product): ?Product
    {
        if (!$product->isEnabled()) {
            return null;
        }

        if (!$product->getProduct()->isEnabled()) {
            return null;
        }


        $name = !empty($product->getName()) ? $product->getName() : $product->getProduct()->getName();

        $link = $this->router->generate('sylius_shop_product_show',
            ['_locale' => 'pl_PL', 'slug' => $product->getProduct()->getSlug()], UrlGeneratorInterface::ABSOLUTE_URL);

        $path = $product->getProduct()->getImages()->count() ? $product->getProduct()->getImages()->first()->getPath() : null;
        if ($path) {
            $image = $this->router->generate('liip_imagine_filter', ['path' => $path], UrlGeneratorInterface::ABSOLUTE_URL);
        } else {
            $image = null;
        }

        $price = $product->getChannelPricings()->first() ? ($product->getChannelPricings()->first()->getPrice() / 100) : 0;

        $description = !empty($product->getProduct()->getDescription()) ? $product->getProduct()->getDescription() : $product->getProduct()->getName();


        $item = new Product();

        // Set common product properties
        $item->setId($product->getId());
        $item->setTitle($name);
        $item->setDescription($description);
        $item->setLink($link);
        if ($image) {
            $item->setImage($image);
        }
        if ($this->availabilityChecker->isStockAvailable($product)) {
            $item->setAvailability(Availability::IN_STOCK);
        } else {
            $item->setAvailability(Availability::OUT_OF_STOCK);
        }
        $item->setPrice("{$price} PLN");

        $item->setCondition('new');

        // Add this product to the feed
        return $item;
    }

    private function saveFeed(Feed $feed): string
    {
        // Here we get complete XML of the feed, that we could write to file or send directly
        $feedXml = $feed->build();

        $publicDirectory = $this->parameterBag->get('kernel.project_dir').'/public'; // Pobierz ścieżkę do folderu public

        $filePath = $publicDirectory.'/output.xml';

        file_put_contents($filePath, $feedXml);

        return $filePath;
    }

}
