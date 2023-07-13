<?php

namespace App\Form\Extension\Product;

use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use EWZ\Bundle\RecaptchaBundle\Validator\Constraints\IsTrue as RecaptchaTrue;
use Sylius\Bundle\CoreBundle\Form\Type\Product\ProductReviewType as SyliusProductReviewType;
use Sylius\Bundle\ReviewBundle\Form\Type\ReviewType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;

class ProductReviewType extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('recaptcha', EWZRecaptchaType::class, array(
            'attr'        => array(
                'options' => array(
                    'theme' => 'light',
                    'type'  => 'image',
                    'size'  => 'normal'
                )
            ),
            'mapped'      => false,
            'constraints' => array(
                new RecaptchaTrue()
            )
        ));
    }

    public static function getExtendedTypes(): iterable
    {
        return [SyliusProductReviewType::class];
    }
}
