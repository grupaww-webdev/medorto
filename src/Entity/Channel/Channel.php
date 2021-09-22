<?php

declare(strict_types=1);

namespace App\Entity\Channel;

use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusVendorPlugin\Entity\VendorsAwareInterface;
use Odiseo\SyliusVendorPlugin\Entity\VendorsTrait;
use Sylius\Component\Core\Model\Channel as BaseChannel;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_channel")
 */
class Channel extends BaseChannel implements FacebookMessengerAwareInterface, VendorsAwareInterface
{
    use FacebookMessengerAwareTrait;
    use VendorsTrait {
        __construct as private initializeVendorsCollection;
    }

    public function __construct()
    {
        parent::__construct();
        $this->initializeVendorsCollection();
    }
}
