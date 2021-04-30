<?php

declare(strict_types=1);

namespace App\Form\Type\Checkout;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\{
    ChangePaymentMethodType,
    PaymentType,
    ShipmentType
};
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;

final class SelectShippingAndPaymentType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('shipments', CollectionType::class, [
                'entry_type' => ShipmentType::class,
                'label' => false,
            ])
            ->add('payments', ChangePaymentMethodType::class, [
                'entry_type' => PaymentType::class,
                'label' => false,
            ])
            ->add('rules', CheckboxType::class, [
                'mapped' => false
            ]);
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_checkout_select_shipping_and_payment';
    }
}
