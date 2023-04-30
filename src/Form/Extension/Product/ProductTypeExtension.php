<?php

namespace App\Form\Extension\Product;

use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Sylius\Bundle\ProductBundle\Form\Type\ProductType;
use Sylius\Bundle\ResourceBundle\Form\EventSubscriber\AddCodeFormSubscriber;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

final class ProductTypeExtension extends AbstractTypeExtension
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $preSetData = $builder->getEventDispatcher()->getListeners('form.pre_set_data');

        foreach ($preSetData as $datum) {
            $event = $datum[0];

            // Remove code edit blocker todo: check the impact
            if($event instanceof AddCodeFormSubscriber)
                $builder->getEventDispatcher()->removeSubscriber($event);
        }

        $builder
            ->add('code', TextType::class,
                [
                    'label' => 'sylius.ui.code',
                    'required' => true
                ]
            )
            ->add('bestseller', CheckboxType::class, [
                'required' => false,
                'label' => 'sylius.form.product.bestseller',
            ])
            ->add('new', CheckboxType::class, [
                'required' => false,
                'label' => 'sylius.form.product.new',
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ProductType::class];
    }

}
