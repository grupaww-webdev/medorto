<?php

declare(strict_types=1);

namespace App\Form\Type\Shipping\Calculator;

use Sylius\Bundle\CoreBundle\Form\Type\ChannelCollectionType;
use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class FreeCalculatorType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        $builder
            ->add('amount', MoneyType::class, [
                'label' => 'app.form.shipping_calculator.free_above_configuration.amount',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Range(['min' => 0, 'minMessage' => 'sylius.shipping_method.calculator.min', 'groups' => ['sylius']]),
                    new Type(['type' => 'integer', 'groups' => ['sylius']]),
                ],
                'currency' => $options['currency'],
            ])
            ->add('limit', MoneyType::class, [
                'label' => 'app.form.shipping_calculator.free_above_configuration.limit',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Range(['min' => 0, 'minMessage' => 'sylius.shipping_method.calculator.min', 'groups' => ['sylius']]),
                    new Type(['type' => 'integer', 'groups' => ['sylius']]),
                ],
                'currency' => $options['currency'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {


        $resolver
            ->setDefaults([
                'data_class' => null,
                'currency' => 'PLN',
                'limit' => '0'
            ])
            ->setRequired('currency')
            ->setAllowedTypes('currency', 'string')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix(): string
    {
        return 'app_shipping_calculator_free';
    }

}
