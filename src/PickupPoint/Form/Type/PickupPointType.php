<?php

declare(strict_types=1);

namespace App\PickupPoint\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class PickupPointType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'app.form.pickup_point.name'
            ])
            ->add('address', TextareaType::class, [
                'label' => 'app.form.pickup_point.address'
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'app.form.pickup_point.zip_code'
            ])
            ->add('city', TextType::class, [
                'label' => 'app.form.pickup_point.city'
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'app.form.pickup_point.active'
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_refund_form_type';
    }
}
