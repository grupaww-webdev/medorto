<?php

declare(strict_types=1);

namespace App\Form\Type;

use Sylius\Bundle\ResourceBundle\Form\Type\AbstractResourceType;
use Symfony\Component\Form\FormBuilderInterface;

class RefundType extends AbstractResourceType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

    }

    public function getBlockPrefix(): string
    {
        return 'app_refund_form_type';
    }
}
