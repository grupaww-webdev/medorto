<?php

declare(strict_types=1);

namespace App\Form\Extension;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Odiseo\BlogBundle\Form\Type\ArticleTranslationType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleTranslationTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);
        $builder
            ->add('intro', CKEditorType::class, [
                'label' => 'odiseo_blog.form.article.intro',
                'required' => true,
            ])
            ->add('first_paragraph', CKEditorType::class, [
                'label' => 'odiseo_blog.form.article.first_paragraph',
                'required' => true,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public static function getExtendedTypes(): iterable
    {
        return [ArticleTranslationType::class];
    }
}
