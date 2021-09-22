<?php
declare(strict_types=1);

namespace App\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class ChannelTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder
            ->add('enableFacebookMessenger', CheckboxType::class, [
                'label' => 'app.ui.enable_facebook_messenger',
                'required' => false,
            ])
            ->add('facebookPageId', TextType::class, [
                'label' => 'app.ui.facebook_page_id',
                'required' => false,
            ]);
    }

    public static function getExtendedTypes(): iterable
    {
        return [
            ChannelType::class
        ];
    }
}
