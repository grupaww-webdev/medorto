<?php

declare(strict_types=1);

namespace App\Entity\Addressing;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Address as BaseAddress;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_address")
 */
class Address extends BaseAddress
{

    /**
     * @var string|null
     * @ORM\Column(type="string", nullable=true)
     */
    protected $nip;

    /**
     * @return string|null
     */
    public function getNip(): ?string
    {
        return $this->nip;
    }

    /**
     * @param   string|null  $nip
     */
    public function setNip(?string $nip): void
    {
        $this->nip = $nip;
    }


}
