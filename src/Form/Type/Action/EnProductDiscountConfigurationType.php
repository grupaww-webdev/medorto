<?php

namespace App\Form\Type\Action;

use Sylius\Bundle\PromotionBundle\Form\DataTransformer\PercentFloatToLocalizedStringTransformer;
use Sylius\Bundle\PromotionBundle\Form\Type\PromotionFilterCollectionType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PercentType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;

class EnProductDiscountConfigurationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('percentage', PercentType::class, [
                'label' => 'sylius.form.promotion_action.percentage_discount_configuration.percentage',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'numeric', 'groups' => ['sylius']]),
                    new Range([
                        'min' => 0,
                        'max' => 1,
                        'notInRangeMessage' => 'sylius.promotion_action.percentage_discount_configuration.not_in_range',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
            ->add('enty', IntegerType::class, [
                'label' => 'sylius.form.promotion_action.percentage_discount_configuration.enty',
                'constraints' => [
                    new NotBlank(['groups' => ['sylius']]),
                    new Type(['type' => 'numeric', 'groups' => ['sylius']]),
                    new Range([
                        'min' => 2,
                        'max' => 10,
                        'notInRangeMessage' => 'sylius.promotion_action.percentage_discount_configuration.not_in_range',
                        'groups' => ['sylius'],
                    ]),
                ],
            ])
        ;
        $builder->get('percentage')->resetViewTransformers()->addViewTransformer(new PercentFloatToLocalizedStringTransformer());
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'app_promotion_action_en_product_discount_configuration';
    }
}
