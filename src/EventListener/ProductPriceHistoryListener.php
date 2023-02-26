<?php

namespace App\EventListener;

use App\Entity\Channel\ChannelPricing;
use App\Entity\Channel\ChannelPricingHistory;
use App\Entity\Product\Product;
use App\Entity\Product\ProductVariant;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Sylius\Component\Core\Model\ImagesAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\Messenger\MessageBusInterface;
use Webmozart\Assert\Assert;

final class ProductPriceHistoryListener
{

    const KEY = 'price';

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private ContainerInterface $container;

    private bool $debug;


    public function __construct(
        ContainerInterface $container,
        bool $debug = false
    ) {
        $this->container = $container;
        $this->debug     = $debug;
    }

    public function postUpdate(LifecycleEventArgs $args)
    {
        $manager = $args->getEntityManager();
        $uof     = $manager->getUnitOfWork();
        $entity  = $args->getEntity();

        if (!$this->isPriceChanged($uof->getEntityChangeSet($entity)))
        {
            return true;
        }

        Assert::isInstanceOf($entity, ChannelPricing::class);

        $history = $this->saveHistoryChannelPrices($entity);

        $manager->persist($history);
        $manager->flush();

//        dump(
//            $manager,
//            $entity,
//            $history,
//            $uof->getEntityChangeSet($entity),
//            $this->isPriceChanged(
//                $uof->getEntityChangeSet($entity)
//            ) === false
//        );
//        die;
    }

    protected function isPriceChanged(array $changeSet): bool
    {
        return array_key_exists(self::KEY, $changeSet);
    }

//    public function handleProduct(GenericEvent $event)
//    {
//        /** @var Product $subject */
//        $subject = $event->getSubject();
//        Assert::isInstanceOf($subject, Product::class);
//
//        dump($subject, $event);
//        die;
//    }
//
//    public function handleProductVariant(GenericEvent $event)
//    {
//        /** @var ProductVariant $subject */
//        $subject = $event->getSubject();
//
//        Assert::isInstanceOf($subject, ProductVariant::class);
//
//        dump(
//            $subject->getChannelPricings()->toArray(),
//            $event
//        );
//    }

    protected function saveHistoryChannelPrices(ChannelPricing $channelPricing)
    {
        $channelPricingHistory = new ChannelPricingHistory();
        $channelPricingHistory->setChannelPricing($channelPricing);
        $channelPricingHistory->setNewPrice($channelPricing->getPrice());
        $channelPricingHistory->setChangeDate(new \DateTime());

        return $channelPricingHistory;
    }

}
