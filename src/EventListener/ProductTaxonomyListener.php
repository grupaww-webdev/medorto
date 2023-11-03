<?php

namespace App\EventListener;

use App\Entity\Channel\ChannelPricing;
use App\Entity\Product\ProductTaxon;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class ProductTaxonomyListener
{
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

        if(!$entity instanceof ProductTaxon)
        {
            return true;
        }

        dd($entity);

        return true;
    }
}
