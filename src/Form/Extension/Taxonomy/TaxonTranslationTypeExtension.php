<?php

namespace App\Form\Extension\Taxonomy;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\TaxonomyBundle\Form\Type\TaxonTranslationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

final class TaxonTranslationTypeExtension extends AbstractTypeExtension
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
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [TaxonTranslationType::class];
    }
}
