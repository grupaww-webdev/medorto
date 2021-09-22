<?php

declare(strict_types=1);

namespace App\Entity\Shipping;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\UuidInterface;
use Sylius\Component\Resource\Model\ResourceInterface;

/**
 * @ORM\Entity
 * @ORM\Table(name="app_shipment_pickup_point")
 */
class PickupPoint implements PickupPointInterface, ResourceInterface
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid")
     * @var UuidInterface
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $address;

    /**
     * @ORM\Column(type="string", name="zip_code", length=15)
     * @var string
     */
    protected $zipCode;

    /**
     * @ORM\Column(type="string", length=255)
     * @var string
     */
    protected $city;

    /**
     * @ORM\Column(type="boolean", name="active", options={"default": false})
     * @var bool
     */
    protected $active;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getZipCode(): string
    {
        return $this->zipCode;
    }

    public function getCity(): string
    {
        return $this->city;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setAddress(string $address): void
    {
        $this->address = $address;
    }

    public function setZipCode(string $zipCode): void
    {
        $this->zipCode = $zipCode;
    }

    public function setCity(string $city): void
    {
        $this->city = $city;
    }

    public function setActive(bool $active): void
    {
        $this->active = $active;
    }

    public function deactivate(): void
    {
        $this->active = false;
    }

    public function activate(): void
    {
        $this->active = true;
    }

    public function serialize(): array
    {
        return [
            'active' => $this->active,
            'city' => $this->city,
            'address' => $this->address,
            'zipCode' => $this->zipCode,
            'id' => $this->id->toString(),
            'name' => $this->name
        ];
    }
}
