<?php

declare(strict_types=1);

namespace Reservation\Application\Command;

use Exception;
use OpenApi\Domain\HasOpenApiSpec;
use Reservation\Application\Dto\AddressDto;
use Reservation\Application\Dto\ArrivalTimeDto;
use Reservation\Application\Dto\InventoryItemsDto;
use Reservation\Application\Dto\LineItemsDto;
use Reservation\Application\Dto\RentDurationDto;
use Reservation\Application\Dto\UserDto;
use Reservation\Application\Factory\Factory;

final class UpdateReservation implements HasOpenApiSpec
{
    private string $reservationId;
    private string $customerId;
    private string $currency;
    private RentDurationDto $rentDurationDto;
    private LineItemsDto $lineItemsDto;
    private InventoryItemsDto $inventoryItemsDto;
    private string $note;
    private ArrivalTimeDto $toBeDeliveredAt;
    private ArrivalTimeDto $toBePickedUpAt;
    private string $toBeDeliveredBy;
    private string $toBePickedUpBy;
    private string $saleChannelId;
    private string $reservationStatus;
    private UserDto $userDto;
    private AddressDto $addressDto;

    public function __construct(
        string $reservationId,
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
        UserDto $userDto
    ) {
        $this->lineItemsDto = $lineItemsDto;
        $this->inventoryItemsDto = $inventoryItemsDto;
        $this->reservationId = $reservationId;
        $this->customerId = $customerId;
        $this->currency = $currency;
        $this->rentDurationDto = $rentDurationDto;
        $this->note = $note;
        $this->toBeDeliveredAt = $toBeDeliveredAt;
        $this->toBePickedUpAt = $toBePickedUpAt;
        $this->toBeDeliveredBy = $toBeDeliveredBy;
        $this->toBePickedUpBy = $toBePickedUpBy;
        $this->saleChannelId = $saleChannelId;
        $this->reservationStatus = $reservationStatus;
        $this->addressDto = $addressDto;
        $this->userDto = $userDto;
    }

    public function reservationId(): string
    {
        return $this->reservationId;
    }

    public function addressDto(): AddressDto
    {
        return $this->addressDto;
    }

    public function customerId(): string
    {
        return $this->customerId;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function rentDurationDto(): RentDurationDto
    {
        return $this->rentDurationDto;
    }

    public function note(): string
    {
        return $this->note;
    }

    public function toBeDeliveredAt(): ArrivalTimeDto
    {
        return $this->toBeDeliveredAt;
    }

    public function toBePickedUpAt(): ArrivalTimeDto
    {
        return $this->toBePickedUpAt;
    }

    public function toBeDeliveredBy(): string
    {
        return $this->toBeDeliveredBy;
    }

    public function toBePickedUpBy(): string
    {
        return $this->toBePickedUpBy;
    }

    public function saleChannelId(): string
    {
        return $this->saleChannelId;
    }

    public function reservationStatus(): string
    {
        return $this->reservationStatus;
    }

    public function userDto(): UserDto
    {
        return $this->userDto;
    }

    public function lineItemsDto(): LineItemsDto
    {
        return $this->lineItemsDto;
    }

    public function inventoryItemsDto(): InventoryItemsDto
    {
        return $this->inventoryItemsDto;
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data = []): UpdateReservation
    {
        return Factory::updateReservation($data);
    }
}
