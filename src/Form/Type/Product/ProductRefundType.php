<?php

declare(strict_types=1);

namespace App\Form\Type\Product;

use Sylius\Bundle\MoneyBundle\Form\Type\MoneyType;
use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class ProductRefundType  extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'app.form.product_refund.code'
            ])
//            ->add('discount', NumberType::class, [
//                'label' => 'app.form.product_refund.discount',
//                'attr' => [
//                    'min' => 1,
//                    'max' => 100
//                ],
//                'constraints' => [
//                    new NotBlank(),
//                    new Range(['min' => 1, 'max' => 100])
//                ]
//            ])
            ->add('discountPiece', MoneyType::class, [
                'label' => 'app.form.product_refund.discountPiece',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('discountPack', MoneyType::class, [
                'label' => 'app.form.product_refund.discountPack',
                'constraints' => [
                    new NotBlank(),
                ]
            ])
            ->add('description', TextareaType::class, [
                'label' => 'app.form.product_refund.description'
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'app.form.product_refund.active'
            ])
        ;
    }

    public function getBlockPrefix(): string
    {
        return 'app_product_refund_form_type';
    }
}
