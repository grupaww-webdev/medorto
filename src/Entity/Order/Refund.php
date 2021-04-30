<?php

declare(strict_types=1);

namespace App\Entity\Order;

use Sylius\Component\Resource\Model\ResourceInterface;
use Doctrine\ORM\Mapping AS ORM;
use Sylius\Component\Resource\Model\TimestampableTrait;

/**
 * @ORM\Entity
 * @ORM\Table(name="sylius_refund_order")
 */
class Refund implements ResourceInterface
{
    use TimestampableTrait;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @ORM\Column(type="datetime", name="order_date")
     * @var \DateTimeInterface
     */
    protected $orderDate;

    /**
     * @ORM\Column(type="datetime", name="order_received_date")
     * @var \DateTimeInterface
     */
    protected $orderReceivedDate;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $name;

    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $address;

    /**
     * @ORM\Column(type="string", name="bank_address")
     * @var string
     */
    protected $bankAccount;

    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $city;

    /**
     * @ORM\Column(type="datetime", name="issue_date")
     * @var \DateTimeInterface
     */
    protected $issueDate;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     * @var \DateTimeInterface|null
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="datetime", name="updated_at")
     * @var \DateTimeInterface|null
     */
    protected $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getOrderDate(): ?\DateTimeInterface
    {
        return $this->orderDate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getOrderReceivedDate(): ?\DateTimeInterface
    {
        return $this->orderReceivedDate;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @return string|null
     */
    public function getBankAccount(): ?string
    {
        return $this->bankAccount;
    }

    /**
     * @return string|null
     */
    public function getCity(): ?string
    {
        return $this->city;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getIssueDate(): ?\DateTimeInterface
    {
        return $this->issueDate;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
}
