<?php

declare(strict_types=1);

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

final class AccountMenuBuilder
{
    public const EVENT_NAME = 'sylius.menu.shop.account';

    /** @var FactoryInterface */
    private $factory;

    /** @var EventDispatcherInterface */
    private $eventDispatcher;

    public function __construct(FactoryInterface $factory, EventDispatcherInterface $eventDispatcher)
    {
        $this->factory = $factory;
        $this->eventDispatcher = $eventDispatcher;
    }

    public function createMenu(array $options): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setLabel('sylius.menu.shop.account.header');

        $menu
            ->addChild('order_history', ['route' => 'sylius_shop_account_order_index'])
            ->setLabel('sylius.menu.shop.account.order_history')
            ->setLabelAttribute('image', 'orders')
        ;
        $menu
            ->addChild('refunds', ['route' => 'sylius_shop_account_order_index'])
            ->setLabel('sylius.menu.shop.account.refunds')
            ->setLabelAttribute('image', 'backs')
        ;
        $menu
            ->addChild('personal_information', ['route' => 'sylius_shop_account_profile_update'])
            ->setLabel('sylius.menu.shop.account.account_settings')
            ->setLabelAttribute('image', 'settings')
        ;
        $menu
            ->addChild('change_password', ['route' => 'sylius_shop_account_change_password'])
            ->setLabel('sylius.menu.shop.account.change_password')
            ->setLabelAttribute('image', 'change')
        ;

        $this->eventDispatcher->dispatch(new MenuBuilderEvent($this->factory, $menu), self::EVENT_NAME);

        return $menu;
    }
}
