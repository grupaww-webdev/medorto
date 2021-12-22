<?php

declare(strict_types=1);

namespace App\Calculator;

use App\Entity\Shipping\Shipment;
use Sylius\Component\Core\Exception\MissingChannelConfigurationException;
use Sylius\Component\Shipping\Calculator\CalculatorInterface;
use Sylius\Component\Shipping\Calculator\UndefinedShippingMethodException;
use Sylius\Component\Shipping\Model\ShipmentInterface;

final class ShippingCostCalculator implements CalculatorInterface
{
    /**
     * @param   Shipment  $subject
     *
     * {@inheritdoc}
     */
    public function calculate(ShipmentInterface $subject, array $configuration): int
    {
        /**
         * @var \App\Entity\Channel\Channel $channel
         */
        $channel = $subject->getOrder()->getChannel();

        if(!array_key_exists($channel->getCode(), $configuration))
            throw new MissingChannelConfigurationException('Cannot calculate charge for shipment without a defined shipping channel options.');

        $channelConfiguration = $configuration[$channel->getCode()];

        if ($subject->getOrder()->getTotal() > $channelConfiguration['limit']) {
            return 0;
        }

       return $channelConfiguration['amount'];
    }

    /**
     * {@inheritdoc}
     */
    public function getType(): string
    {
        return 'free';
    }

}
