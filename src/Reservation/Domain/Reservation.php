<?php

declare(strict_types=1);

namespace Reservation\Domain;

use DateTimeImmutable;
use Lib\DateTimeLib;
use Money\Currency;
use Reservation\Application\Command\DeleteReservation;
use Reservation\Application\Command\UpdateReservation;
use Reservation\Application\Dto\AddressDto;
use Reservation\Application\Dto\ArrivalTimeDto;
use Reservation\Application\Dto\InventoryItemsDto;
use Reservation\Application\Dto\LineItemsDto;
use Reservation\Application\Dto\RentDurationDto;
use Reservation\Domain\Address\Address;
use Reservation\Domain\Collection\InventoryItems;
use Reservation\Domain\Collection\LineItems;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Exception\InvalidArrivalTime;
use Reservation\Domain\Exception\LineItemNotFound;
use Reservation\Domain\Exception\ReservationAlreadyDeleted;
use Reservation\Domain\Identity\CompanyId;
use Reservation\Domain\Identity\CustomerId;
use Reservation\Domain\Identity\LineItemId;
use Reservation\Domain\Identity\ReservationId;
use Reservation\Domain\Identity\SaleChannelId;
use Reservation\Domain\Identity\UserId;
use Reservation\Domain\LineItem\LineItem;

// check for duplicated IDs, line items, inventory items, step products...
final class Reservation
{
    private ReservationId $reservationId;
    private CompanyId $companyId;
    private CustomerId $customerId;
    private Address $address;
    private Currency $currency;
    private RentDuration $rentDuration;
    private Note $note;
    private ArrivalTime $toBeDeliveredAt;
    private ArrivalTime $toBePickedUpAt;
    private UserId $toBeDeliveredBy;
    private UserId $toBePickedUpBy;
    private LineItems $lineItems;
    private InventoryItems $inventoryItems;
    private SaleChannelId $saleChannelId;
    private ReservationStatus $reservationStatus;
    private UserId $createdBy;
    private DateTimeImmutable $createdAt;
    private UserId $modifiedBy;
    private DateTimeImmutable $modifiedAt;
    private ?UserId $deletedBy;
    private ?DateTimeImmutable $deletedAt;

    public function __construct(
        ReservationId $reservationId,
        CompanyId $companyId,
        CustomerId $customerId,
        LineItems $lineItems,
        InventoryItems $inventoryItems,
        Currency $currency,
        Address $address,
        RentDuration $rentDuration,
        ArrivalTime $toBeDeliveredAt,
        ArrivalTime $toBePickedUpAt,
        UserId $toBeDeliveredBy,
        UserId $toBePickedUpBy,
        ReservationStatus $reservationStatus,
        SaleChannelId $saleChannelId,
        Note $note,
        UserId $createdBy,
        DateTimeImmutable $createdAt,
        UserId $modifiedBy,
        DateTimeImmutable $modifiedAt,
        ?UserId $deletedBy,
        ?DateTimeImmutable $deletedAt
    ) {
        $this->reservationId = $reservationId;
        $this->address = $address;
        $this->customerId = $customerId;
        $this->companyId = $companyId;
        $this->lineItems = $lineItems;
        $this->inventoryItems = $inventoryItems;
        $this->currency = $currency;
        $this->rentDuration = $rentDuration;
        $this->note = $note;
        $this->toBeDeliveredAt = $toBeDeliveredAt;
        $this->toBePickedUpAt = $toBePickedUpAt;
        $this->toBeDeliveredBy = $toBeDeliveredBy;
        $this->toBePickedUpBy = $toBePickedUpBy;
        $this->saleChannelId = $saleChannelId;
        $this->reservationStatus = $reservationStatus;
        $this->createdBy = $createdBy;
        $this->createdAt = $createdAt;
        $this->modifiedBy = $modifiedBy;
        $this->modifiedAt = $modifiedAt;
        $this->deletedBy = $deletedBy;
        $this->deletedAt = $deletedAt;
    }

    /**
     * @throws InvalidArrivalTime
     * @throws InvalidArgument
     */
    public static function create(
        string $reservationId,
        string $companyId,
        string $customerId,
        LineItemsDto $lineItemsDto,
        InventoryItemsDto $inventoryItemsDto,
        string $currency,
        AddressDto $addressDto,
        RentDurationDto $rentDurationDto,
        ArrivalTimeDto $toBeDeliveredAt,
        ArrivalTimeDto $toBePickedUpAt,
        string $toBeDeliveredBy,
        string $toBePickedUpBy,
        string $reservationStatus,
        string $saleChannelId,
        string $note,
        string $createdBy,
        DateTimeImmutable $createdAt,
        string $modifiedBy,
        DateTimeImmutable $modifiedAt,
        ?string $deletedBy,
        ?DateTimeImmutable $deletedAt
    ): Reservation {
        if (empty($currency) || 3 !== strlen($currency)) {
            throw new InvalidArgument('Currency code should be 3 characters long');
        }

        return new Reservation(
            ReservationId::fromString($reservationId),
            CompanyId::fromString($companyId),
            CustomerId::fromString($customerId),
            LineItems::fromDto($lineItemsDto),
            InventoryItems::fromDto($inventoryItemsDto),
            new Currency($currency),
            Address::fromDto($addressDto),
            RentDuration::fromDto($rentDurationDto),
            ArrivalTime::fromDto($toBeDeliveredAt),
            ArrivalTime::fromDto($toBePickedUpAt),
            UserId::fromString($toBeDeliveredBy),
            UserId::fromString($toBePickedUpBy),
            ReservationStatus::from($reservationStatus),
            SaleChannelId::fromString($saleChannelId),
            Note::fromString($note),
            UserId::fromString($createdBy),
            $createdAt,
            UserId::fromString($modifiedBy),
            $modifiedAt,
            null !== $deletedBy ? UserId::fromString($deletedBy) : null,
            $deletedAt
        );
    }

    /**
     * @throws InvalidArgument
     * @throws InvalidArrivalTime
     */
    public function withUpdateReservation(UpdateReservation $command): void
    {
        $this->customerId = CustomerId::fromString($command->customerId());
        $this->currency = MoneyFactory::currency($command->currency());
        $this->lineItems = LineItems::fromDto($command->lineItemsDto());
        $this->inventoryItems = InventoryItems::fromDto($command->inventoryItemsDto());
        $this->address = Address::fromDto($command->addressDto());
        $this->rentDuration = RentDuration::fromDto($command->rentDurationDto());
        $this->toBeDeliveredAt = ArrivalTime::fromDto($command->toBeDeliveredAt());
        $this->toBePickedUpAt = ArrivalTime::fromDto($command->toBePickedUpAt());
        $this->toBeDeliveredBy = UserId::fromString($command->toBeDeliveredBy());
        $this->toBePickedUpBy = UserId::fromString($command->toBePickedUpBy());
        $this->reservationStatus = ReservationStatus::from($command->reservationStatus());
        $this->saleChannelId = SaleChannelId::fromString($command->saleChannelId());
        $this->note = Note::fromString($command->note());
        $this->modifiedBy = UserId::fromString($command->userDto()->userId());
        $this->modifiedAt = DateTimeLib::current();
    }

    /**
     * @throws InvalidArgument
     * @throws ReservationAlreadyDeleted
     */
    public function markAsDeleted(DeleteReservation $deleteReservation): void
    {
        if ($this->isMarkedAsDeleted()) {
            throw new ReservationAlreadyDeleted($this->reservationId);
        }

        $this->deletedAt = DateTimeLib::current();
        $this->deletedBy = UserId::fromString($deleteReservation->userDto()->userId());
    }

    private function isMarkedAsDeleted(): bool
    {
        return null !== $this->deletedBy && null !== $this->deletedAt;
    }

    public function reservationId(): ReservationId
    {
        return $this->reservationId;
    }

    public function address(): Address
    {
        return $this->address;
    }

    public function customerId(): CustomerId
    {
        return $this->customerId;
    }

    public function companyId(): CompanyId
    {
        return $this->companyId;
    }

    public function lineItems(): LineItems
    {
        return $this->lineItems;
    }

    /**
     * @throws LineItemNotFound
     */
    public function lineItem(LineItemId $lineItemId): LineItem
    {
        foreach ($this->lineItems as $lineItem) {
            if ($lineItem->lineItemId()->equals($lineItemId)) {
                return $lineItem;
            }
        }

        throw new LineItemNotFound($lineItemId);
    }

    /**
     * @param array<string> $ids
     */
    public function filterLineItems(array $ids): LineItems
    {
        $lineItems = array_filter($this->lineItems->toArray(), function ($lineItem) use ($ids) {
            return in_array($lineItem->lineItemId()->toString(), $ids);
        });

        return new LineItems(...$lineItems);
    }

    /**
     * @return array<string>
     */
    public function mapLineItemIds(): array
    {
        return array_map(function ($lineItem) {
            return $lineItem->lineItemId()->toString();
        }, $this->lineItems->toArray());
    }

    public function inventoryItems(): InventoryItems
    {
        return $this->inventoryItems;
    }

    /**
     * @param array<string> $ids
     */
    public function filterInventoryItems(array $ids): InventoryItems
    {
        $inventoryItems = array_filter($this->inventoryItems->toArray(), function ($inventoryItem) use ($ids) {
            return in_array($inventoryItem->inventoryItemId()->toString(), $ids);
        });

        return new InventoryItems(...$inventoryItems);
    }

    /**
     * @return array<string>
     */
    public function mapInventoryItemIds(): array
    {
        return array_map(function ($inventoryItem) {
            return $inventoryItem->inventoryItemId()->toString();
        }, $this->inventoryItems->toArray());
    }

    public function currency(): Currency
    {
        return $this->currency;
    }

    public function note(): Note
    {
        return $this->note;
    }

    public function rentDuration(): RentDuration
    {
        return $this->rentDuration;
    }

    public function toBeDeliveredAt(): ArrivalTime
    {
        return $this->toBeDeliveredAt;
    }

    public function toBePickedUpAt(): ArrivalTime
    {
        return $this->toBePickedUpAt;
    }

    public function toBeDeliveredBy(): UserId
    {
        return $this->toBeDeliveredBy;
    }

    public function toBePickedUpBy(): UserId
    {
        return $this->toBePickedUpBy;
    }

    public function saleChannelId(): SaleChannelId
    {
        return $this->saleChannelId;
    }

    public function reservationStatus(): ReservationStatus
    {
        return $this->reservationStatus;
    }

    public function createdBy(): UserId
    {
        return $this->createdBy;
    }

    public function createdAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function modifiedBy(): UserId
    {
        return $this->modifiedBy;
    }

    public function modifiedAt(): DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function deletedBy(): ?UserId
    {
        return $this->deletedBy;
    }

    public function deletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }
}
