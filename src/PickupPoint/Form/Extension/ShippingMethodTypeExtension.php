<?php

declare(strict_types=1);

namespace App\PickupPoint\Form\Extension;

use App\PickupPoint\Manager\ProviderManagerInterface;
use Sylius\Bundle\ShippingBundle\Form\Type\ShippingMethodType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;

final class ShippingMethodTypeExtension extends AbstractTypeExtension
{
    /**
     * @var ProviderManagerInterface
     */
    private $providerManager;

    public function __construct(ProviderManagerInterface $providerManager)
    {
        $this->providerManager = $providerManager;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('pickupPointProvider', ChoiceType::class, [
            'placeholder' => 'app.form.shipping_method.select_pickup_point_provider',
            'label' => 'app.form.shipping_method.pickup_point_provider',
            'choices' => $this->getChoices()
        ]);
    }

    public function getExtendedType(): string
    {
        return ShippingMethodType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [ShippingMethodType::class];
    }

    private function getChoices(): array
    {
        $choices = [];

        foreach ($this->providerManager->all() as $provider) {
            $choices[$provider->getName()] = get_class($provider);
        }

        return $choices;
    }
}
