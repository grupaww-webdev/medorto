<?php

namespace App\EmailManager;

use App\Entity\Order\Order;
use Sylius\Component\Mailer\Sender\SenderInterface;
use Sylius\Component\Order\Model\OrderInterface;

final class OrderNotificationEmailManager
{
    /** @var SenderInterface */
    private $emailSender;

    public function __construct(
        SenderInterface $emailSender
    ) {
        $this->emailSender = $emailSender;
    }

    /**
     * @param   Order  $order
     *
     * @return void
     */
    public function sendPreparationEmail(OrderInterface $order): void
    {

        $this->emailSender->send('order_send_preparation', [$order->getCustomer()->getEmail()], ['order' => $order,'localeCode' => $order->getLocaleCode(),'channel' => $order->getChannel()]);


    }
}
