<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity;

use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Exception;
use Reservation\Domain\ReservationStatus;
use Reservation\Infrastructure\Entity\Embeddable\AddressEmbeddable;
use Reservation\Infrastructure\Entity\Embeddable\ArrivalTimeEmbeddable;
use Reservation\Infrastructure\Entity\Embeddable\RentDurationEmbeddable;

#[Entity]
#[Table(name: 'reservations')]
class ReservationEntity
{
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'NONE')] // because it's set in domain, how does this affect doctrine internals
    private string $reservationId;

    #[Column(type: 'uuid')]
    private string $companyId;

    #[Column(type: 'uuid')]
    private string $customerId;

    #[Embedded]
    private AddressEmbeddable $address;

    #[Column(type: 'string', length: 3)]
    private string $currency;

    #[Embedded]
    private RentDurationEmbeddable $rentDuration;

    #[Column(type: 'text')]
    private string $note;

    #[Embedded]
    private ArrivalTimeEmbeddable $toBeDeliveredAt;

    #[Embedded]
    private ArrivalTimeEmbeddable $toBePickedUpAt;

    #[Column(type: 'uuid')]
    private string $toBeDeliveredBy;

    #[Column(type: 'uuid')]
    private string $toBePickedUpBy;

    /**
     * @var Collection<int, LineItemEntity>
     */
    #[OneToMany(mappedBy: 'reservation', targetEntity: LineItemEntity::class, orphanRemoval: true)]
    private Collection $lineItems;

    /**
     * @var Collection<int, InventoryItemEntity>
     */
    #[OneToMany(mappedBy: 'reservation', targetEntity: InventoryItemEntity::class, orphanRemoval: true)]
    private Collection $inventoryItems;

    #[Column(type: 'uuid')]
    private string $saleChannelId;

    #[Column(type: 'string', enumType: ReservationStatus::class)]
    private string $reservationStatus;

    #[Column(type: 'uuid')]
    private string $createdBy;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $createdAt;

    #[Column(type: 'uuid')]
    private string $modifiedBy;

    #[Column(type: 'datetime_immutable')]
    private DateTimeImmutable $modifiedAt;

    #[Column(type: 'uuid', nullable: true)]
    private ?string $deletedBy;

    #[Column(type: 'datetime_immutable', nullable: true)]
    private ?DateTimeImmutable $deletedAt;

    public function __construct(
//        string $reservationId,
//        string $companyId,
//        string $customerId,
//        AddressEmbeddable $address,
//        PriceEmbeddable $price,
//        RentDurationEmbeddable $rentDuration,
//        string $note,
//        ArrivalTimeEmbeddable $toBeDeliveredAt,
//        ArrivalTimeEmbeddable $toBePickedUpAt,
//        string $toBeDeliveredBy,
//        string $toBePickedUpBy,
//        Collection $lineItems,
//        string $saleChannelId,
//        string $reservationStatus,
//        string $createdBy,
//        DateTimeImmutable $createdAt,
//        string $modifiedBy,
//        DateTimeImmutable $modifiedAt,
//        ?string $deletedBy,
//        ?DateTimeImmutable $deletedAt
    ) {
        $this->lineItems = new ArrayCollection();
//        $this->reservationId = $reservationId;
//        $this->companyId = $companyId;
//        $this->customerId = $customerId;
//        $this->address = $address;
//        $this->price = $price;
//        $this->rentDuration = $rentDuration;
//        $this->note = $note;
//        $this->toBeDeliveredAt = $toBeDeliveredAt;
//        $this->toBePickedUpAt = $toBePickedUpAt;
//        $this->toBeDeliveredBy = $toBeDeliveredBy;
//        $this->toBePickedUpBy = $toBePickedUpBy;
//        $this->lineItems = $lineItems;
//        $this->saleChannelId = $saleChannelId;
//        $this->reservationStatus = $reservationStatus;
//        $this->createdBy = $createdBy;
//        $this->createdAt = $createdAt;
//        $this->modifiedBy = $modifiedBy;
//        $this->modifiedAt = $modifiedAt;
//        $this->deletedBy = $deletedBy;
//        $this->deletedAt = $deletedAt;
    }

    public function getReservationId(): string
    {
        return $this->reservationId;
    }

    public function setReservationId(string $reservationId): ReservationEntity
    {
        $this->reservationId = $reservationId;

        return $this;
    }

    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    public function setCompanyId(string $companyId): ReservationEntity
    {
        $this->companyId = $companyId;

        return $this;
    }

    public function getCustomerId(): string
    {
        return $this->customerId;
    }

    public function setCustomerId(string $customerId): ReservationEntity
    {
        $this->customerId = $customerId;

        return $this;
    }

    public function getAddress(): AddressEmbeddable
    {
        return $this->address;
    }

    public function setAddress(AddressEmbeddable $address): ReservationEntity
    {
        $this->address = $address;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): ReservationEntity
    {
        $this->currency = $currency;

        return $this;
    }

    public function getRentDuration(): RentDurationEmbeddable
    {
        return $this->rentDuration;
    }

    public function setRentDuration(RentDurationEmbeddable $rentDuration): ReservationEntity
    {
        $this->rentDuration = $rentDuration;

        return $this;
    }

    public function getNote(): string
    {
        return $this->note;
    }

    public function setNote(string $note): ReservationEntity
    {
        $this->note = $note;

        return $this;
    }

    public function getToBeDeliveredAt(): ArrivalTimeEmbeddable
    {
        return $this->toBeDeliveredAt;
    }

    public function setToBeDeliveredAt(ArrivalTimeEmbeddable $toBeDeliveredAt): ReservationEntity
    {
        $this->toBeDeliveredAt = $toBeDeliveredAt;

        return $this;
    }

    public function getToBePickedUpAt(): ArrivalTimeEmbeddable
    {
        return $this->toBePickedUpAt;
    }

    public function setToBePickedUpAt(ArrivalTimeEmbeddable $toBePickedUpAt): ReservationEntity
    {
        $this->toBePickedUpAt = $toBePickedUpAt;

        return $this;
    }

    public function getToBeDeliveredBy(): string
    {
        return $this->toBeDeliveredBy;
    }

    public function setToBeDeliveredBy(string $toBeDeliveredBy): ReservationEntity
    {
        $this->toBeDeliveredBy = $toBeDeliveredBy;

        return $this;
    }

    public function getToBePickedUpBy(): string
    {
        return $this->toBePickedUpBy;
    }

    public function setToBePickedUpBy(string $toBePickedUpBy): ReservationEntity
    {
        $this->toBePickedUpBy = $toBePickedUpBy;

        return $this;
    }

    /**
     * @return Collection<int, LineItemEntity>
     */
    public function getLineItems(): Collection
    {
        return $this->lineItems;
    }

    /**
     * @param Collection<int, LineItemEntity> $lineItems
     *
     * @return $this
     */
    public function setLineItems(Collection $lineItems): ReservationEntity
    {
        $this->lineItems = $lineItems;

        return $this;
    }

    /**
     * @throws Exception
     */
    public function getLineItem(string $lineItemId): LineItemEntity
    {
        $lineItem = $this->lineItems->filter(function (LineItemEntity $lineItem) use ($lineItemId) {
            return $lineItem->getLineItemId() === $lineItemId;
        })->first();

        if (!$lineItem) {
            throw new Exception('Line item not found');
        }

        return $lineItem;
    }

    /**
     * @return array<string>
     */
    public function getLineItemIds(): array
    {
        return $this->lineItems->map(function ($lineItemEntity) {
            return $lineItemEntity->getLineItemId();
        })->toArray();
    }

    public function getSaleChannelId(): string
    {
        return $this->saleChannelId;
    }

    public function setSaleChannelId(string $saleChannelId): ReservationEntity
    {
        $this->saleChannelId = $saleChannelId;

        return $this;
    }

    public function getReservationStatus(): string
    {
        return $this->reservationStatus;
    }

    public function setReservationStatus(string $reservationStatus): ReservationEntity
    {
        $this->reservationStatus = $reservationStatus;

        return $this;
    }

    public function getCreatedBy(): string
    {
        return $this->createdBy;
    }

    public function setCreatedBy(string $createdBy): ReservationEntity
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): ReservationEntity
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getModifiedBy(): string
    {
        return $this->modifiedBy;
    }

    public function setModifiedBy(string $modifiedBy): ReservationEntity
    {
        $this->modifiedBy = $modifiedBy;

        return $this;
    }

    public function getModifiedAt(): DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(DateTimeImmutable $modifiedAt): ReservationEntity
    {
        $this->modifiedAt = $modifiedAt;

        return $this;
    }

    public function getDeletedBy(): ?string
    {
        return $this->deletedBy;
    }

    public function setDeletedBy(?string $deletedBy): ReservationEntity
    {
        $this->deletedBy = $deletedBy;

        return $this;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): ReservationEntity
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    /**
     * @return Collection<int, InventoryItemEntity>
     */
    public function getInventoryItems(): Collection
    {
        return $this->inventoryItems;
    }

    /**
     * @param Collection<int, InventoryItemEntity> $inventoryItems
     *
     * @return $this
     */
    public function setInventoryItems(Collection $inventoryItems): ReservationEntity
    {
        $this->inventoryItems = $inventoryItems;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getInventoryItemIds(): array
    {
        return $this->inventoryItems->map(function ($inventoryItemEntity) {
            return $inventoryItemEntity->getInventoryItemId();
        })->toArray();
    }

    /**
     * @throws Exception
     */
    public function getInventoryItem(string $inventoryItemToRemoveId): InventoryItemEntity
    {
        $inventoryItem = $this->inventoryItems->filter(function (InventoryItemEntity $inventoryItem) use ($inventoryItemToRemoveId) {
            return $inventoryItem->getInventoryItemId() === $inventoryItemToRemoveId;
        })->first();

        if (!$inventoryItem) {
            throw new Exception('Inventory item not found');
        }

        return $inventoryItem;
    }
}
