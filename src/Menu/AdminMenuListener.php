<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    public function addRefundMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $sales = $menu->getChild('sales');
        $sales->addChild('refunds', [
                'route' => 'app_admin_order_refund_index'
            ])
            ->setLabel('sylius.admin.menu.refunds')
            ->setLabelAttribute('icon', 'suitcase')
            ;
    }

    public function addPickupPointMenu(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $configuration = $menu->getChild('configuration');
        $configuration->addChild('pickup_points', [
                'route' => 'app_admin_pickup_point_index'
            ])
            ->setLabel('sylius.admin.menu.pickup_points')
            ->setLabelAttribute('icon', 'gift')
        ;
    }
}
