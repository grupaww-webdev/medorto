<?php

namespace App\Form\Extension\Address;

use Sylius\Bundle\AddressingBundle\Form\Type\AddressType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AddressTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Adding new fields works just like in the parent form type.
            ->add('nip', NumberType::class, [
                'required' => false,
                'label' => 'app.form.customer.nip',
            ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [AddressType::class];
    }

}
