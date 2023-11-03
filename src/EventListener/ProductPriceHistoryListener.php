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

        if(!$entity instanceof ChannelPricing)
        {
            return true;
        }

        if (!$this->isPriceChanged($uof->getEntityChangeSet($entity)))
        {
            return true;
        }


        Assert::isInstanceOf($entity, ChannelPricing::class);

        $oldPrice = $this->oldPrice($uof->getEntityChangeSet($entity));

        $history = $this->saveHistoryChannelPrices($entity, $oldPrice);

        $manager->persist($history);
        $manager->flush();

        return true;
    }

    protected function isPriceChanged(array $changeSet): bool
    {
        return array_key_exists(self::KEY, $changeSet);
    }

    protected function saveHistoryChannelPrices(ChannelPricing $channelPricing, int $price): ChannelPricingHistory
    {
        $channelPricingHistory = new ChannelPricingHistory();
        $channelPricingHistory->setChannelPricing($channelPricing);
        $channelPricingHistory->setNewPrice($price);
        $channelPricingHistory->setChangeDate(new \DateTime());

        return $channelPricingHistory;
    }

    protected function oldPrice(array $changeSet): int
    {
        return  $changeSet[self::KEY][0] ?? 0;
    }

}
