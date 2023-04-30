<?php

namespace App\Menu;

use Sylius\Bundle\AdminBundle\Event\ProductMenuBuilderEvent;

final class AdminProductFormMenuListener
{
    public function addItems(ProductMenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();

        $menu
            ->addChild('options')
            ->setAttribute('template', '@SyliusAdmin/Product/Tab/_options.html.twig')
            ->setLabel('sylius.ui.options')
        ;
    }
}
