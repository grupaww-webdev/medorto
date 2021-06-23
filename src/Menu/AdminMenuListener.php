<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

final class AdminMenuListener
{
    /**
     * @param MenuBuilderEvent $event
     */
    public function addRefundMenu(MenuBuilderEvent $event)
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
}
