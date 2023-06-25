<?php

declare(strict_types=1);

namespace App\Form\Extension\Product;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductTranslationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductTranslationTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->remove('description')
            ->add('description', CKEditorType::class, [
                'required' => false,
                'label' => 'sylius.form.product.description',
            ])
            ->add('shippingLabel', TextType::class, [
                'required' => false,
                'label' => 'sylius.form.product.shippingLabel',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [ProductTranslationType::class];
    }
}
