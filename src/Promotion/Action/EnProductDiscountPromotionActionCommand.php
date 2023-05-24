<?php

namespace App\Promotion\Action;

use App\Entity\Order\OrderItemInterface;
use App\Entity\Product\ProductVariant;
use App\Form\Type\Action\EnProductDiscountConfigurationType;
use Sylius\Component\Core\Distributor\ProportionalIntegerDistributorInterface;
use Sylius\Component\Core\Promotion\Action\DiscountPromotionActionCommand;
use Sylius\Component\Core\Promotion\Applicator\UnitsPromotionAdjustmentsApplicatorInterface;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Promotion\Model\PromotionInterface;
use Sylius\Component\Promotion\Model\PromotionSubjectInterface;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class EnProductDiscountPromotionActionCommand extends DiscountPromotionActionCommand
{
    const TYPE = 'cheapest_item_discount';

    /**
     * @var ProportionalIntegerDistributorInterface
     */
    private $proportionalDistributor;

    /**
     * @var UnitsPromotionAdjustmentsApplicatorInterface
     */
    private $unitsPromotionAdjustmentsApplicator;

    /**
     * @param ProportionalIntegerDistributorInterface $proportionalIntegerDistributor
     * @param UnitsPromotionAdjustmentsApplicatorInterface $unitsPromotionAdjustmentsApplicator
     */
    public function __construct(
        ProportionalIntegerDistributorInterface      $proportionalIntegerDistributor,
        UnitsPromotionAdjustmentsApplicatorInterface $unitsPromotionAdjustmentsApplicator
    )
    {
        $this->proportionalDistributor = $proportionalIntegerDistributor;
        $this->unitsPromotionAdjustmentsApplicator = $unitsPromotionAdjustmentsApplicator;
    }

    /**
     * {@inheritdoc}
     */
    public function execute(PromotionSubjectInterface $subject, array $configuration, PromotionInterface $promotion): bool
    {
        if (!$subject instanceof OrderInterface) {
            throw new UnexpectedTypeException($subject, OrderInterface::class);
        }

        $items = $subject->getItems();
        $itemsTotals = [];

        $discountTotals = 0;

        /** @var OrderItemInterface $item */
        foreach ($items as $item) {
            $itemsTotals[] = $item->getTotal();

            $discountTotals += $this->calculateDiscount($item, $configuration);
        }

        $splitPromotion = $this->proportionalDistributor->distribute($itemsTotals, -1 * $discountTotals);
        $this->unitsPromotionAdjustmentsApplicator->apply($subject, $promotion, $splitPromotion);

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function getConfigurationFormType()
    {
        return EnProductDiscountConfigurationType::class;
    }

    protected function isConfigurationValid(array $configuration): void
    {
        // TODO: Implement isConfigurationValid() method.
    }

    protected function calculateDiscount(OrderItemInterface $item, array $configuration): int
    {
        $percentage = $configuration['percentage'];
        $enty = $configuration['enty'];

        $price = $item->getUnitPrice();
        $quantity = $item->getQuantity();

        if ($quantity >= $enty) {
            $floor = floor($quantity / $enty);
            $discount = ($price * $percentage);

            return floor($floor * $discount);
        }

        return 0;
    }
}
