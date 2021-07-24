<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Shipment as BaseShipment;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_shipment")
 */
class Shipment extends BaseShipment implements PickupPointAwareInterface
{
    use PickupPointTrait;

    /**
     * @var string|null
     * @ORM\ManyToOne(targetEntity="PickupPoint", inversedBy="pickupPoint")
     * @ORM\JoinColumn(name="pick_up_point_id", referencedColumnName="id")
     */
    protected $pickupPoint;
}
