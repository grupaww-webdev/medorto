<?php

declare(strict_types=1);

namespace App\Entity\Channel;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;
use Sylius\Component\Core\Model\ChannelPricing as BaseChannelPricing;
use Sylius\Component\Core\Model\ProductInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel_pricing")
 */
class ChannelPricing extends BaseChannelPricing
{
    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Channel\ChannelPricingHistory", mappedBy="channelPricing",
     *                                                                         orphanRemoval=true)
     */
    protected Collection $historyPrices;

    public function __construct()
    {
        $this->historyPrices = new ArrayCollection();
    }

    /**
     * @return int|null
     * @Serializer\VirtualProperty
     */
    public function lastMinimumPrice(): ?int
    {
        $date = (new DateTime())->modify('-1 month')->modify("midnight");
        $price = max($this->getPrice(), $this->getOriginalPrice());

        /** @var \App\Entity\Channel\ChannelPricingHistory $historyPrice */
        foreach($this->historyPrices as $historyPrice)
        {
            if($historyPrice->getNewPrice() < $price && $date->getTimestamp() <= $historyPrice->getChangeDate()->getTimestamp())
                $price = $historyPrice->getNewPrice();
        }
        return $price;
    }


}
