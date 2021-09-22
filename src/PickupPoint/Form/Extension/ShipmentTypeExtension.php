<?php

declare(strict_types=1);

namespace App\PickupPoint\Form\Extension;

use App\Entity\Shipping\Shipment;
use App\PickupPoint\Form\Type\PickupPointIdChoiceType;
use Sylius\Bundle\CoreBundle\Form\Type\Checkout\ShipmentType;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;

final class ShipmentTypeExtension extends AbstractTypeExtension
{
    /**
     * @var RepositoryInterface
     */
    private $pickupPointRepository;

    public function __construct(RepositoryInterface $pickupPointRepository)
    {
        $this->pickupPointRepository = $pickupPointRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pickupPoint', PickupPointIdChoiceType::class, [
                'label' => 'setono_sylius_pickup_point.form.shipment.pickup_point',
                'placeholder' => 'setono_sylius_pickup_point.form.shipment.select_pickup_point',
                'attr' => [
                    'data-shipment-point' => 'id'
                ],
                'required' => true,
                'setter' => function (Shipment &$shipment, ?string $uuid, FormInterface $form): void {
                    if (null === $uuid) {
                        $shipment->setPickupPoint(null);
                        return;
                    }
                    $shipment->setPickupPoint($this->pickupPointRepository->find($uuid));
                }
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ShipmentType::class];
    }
}
