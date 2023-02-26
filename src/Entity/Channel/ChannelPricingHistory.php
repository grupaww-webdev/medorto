<?php

namespace App\Entity\Channel;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel_pricing_history")
 */
class ChannelPricingHistory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @var \App\Entity\Channel\ChannelPricing
     * @ORM\ManyToOne(targetEntity="App\Entity\Channel\ChannelPricing", inversedBy="historyPrices")
     */
    private ChannelPricing $channelPricing;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private int $newPrice;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime")
     */
    private \DateTime $changeDate;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \App\Entity\Channel\ChannelPricing
     */
    public function getChannelPricing(): ChannelPricing
    {
        return $this->channelPricing;
    }

    /**
     * @param   \App\Entity\Channel\ChannelPricing  $channelPricing
     *
     * @return ChannelPricingHistory
     */
    public function setChannelPricing(ChannelPricing $channelPricing
    ): ChannelPricingHistory {
        $this->channelPricing = $channelPricing;

        return $this;
    }

    /**
     * @return int
     */
    public function getNewPrice(): int
    {
        return $this->newPrice;
    }

    /**
     * @param   mixed  $newPrice
     *
     * @return ChannelPricingHistory
     */
    public function setNewPrice(int $newPrice): ChannelPricingHistory
    {
        $this->newPrice = $newPrice;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getChangeDate(): \DateTime
    {
        return $this->changeDate;
    }

    /**
     * @param   mixed  $changeDate
     *
     * @return ChannelPricingHistory
     */
    public function setChangeDate(\DateTime $changeDate): ChannelPricingHistory
    {
        $this->changeDate = $changeDate;

        return $this;
    }


}
