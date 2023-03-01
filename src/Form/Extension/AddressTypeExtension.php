<?php

namespace App\Form\Extension;

use Sylius\Bundle\CoreBundle\Form\Type\Checkout\AddressType;
use Sylius\Component\Core\Model\OrderInterface;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Webmozart\Assert\Assert;

final class AddressTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event): void {
                $form = $event->getForm();

                Assert::isInstanceOf($event->getData(), OrderInterface::class);

                /** @var OrderInterface $order */
                $order = $event->getData();

                if($order->hasRefundItems())
                    $form
                        ->add('refundPesel', TextType::class, [
                            'constraints' => [
                                new NotBlank(['groups' => ['sylius']]),
                                new Length([
                                    'min' => 11,
                                    'groups' => ['sylius'],
                                ])
                            ],
                            'required' => true,
                            'label' => 'sylius.form.checkout.refundPesel',
                        ])
                        ->add('refundCode', TextType::class, [
                            'constraints' => [
                                new NotBlank(['groups' => ['sylius']]),
                            ],
                            'required' => true,
                            'label' => 'sylius.form.checkout.refundCode',
                        ]);
            })
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        return [AddressType::class];
    }
}
