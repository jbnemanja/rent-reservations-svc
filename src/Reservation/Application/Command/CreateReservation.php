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

// todo mozda imati jedan Delivery objekat
final class CreateReservation implements HasOpenApiSpec
{
    private string $customerId;
    private LineItemsDto $lineItemsDto;
    private InventoryItemsDto $inventoryItemsDto;
    private string $currency;
    private AddressDto $addressDto;
    private RentDurationDto $rentDurationDto;
    private ArrivalTimeDto $toBeDeliveredAt;
    private ArrivalTimeDto $toBePickedUpAt;
    private string $toBeDeliveredBy;
    private string $toBePickedUpBy;
    private string $saleChannelId;
    private string $note;
    private UserDto $userDto;

    public function __construct(
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
        string $saleChannelId,
        string $note,
        UserDto $userDto
    ) {
        $this->customerId = $customerId;
        $this->lineItemsDto = $lineItemsDto;
        $this->inventoryItemsDto = $inventoryItemsDto;
        $this->currency = $currency;
        $this->addressDto = $addressDto;
        $this->rentDurationDto = $rentDurationDto;
        $this->toBeDeliveredAt = $toBeDeliveredAt;
        $this->toBePickedUpAt = $toBePickedUpAt;
        $this->toBeDeliveredBy = $toBeDeliveredBy;
        $this->toBePickedUpBy = $toBePickedUpBy;
        $this->saleChannelId = $saleChannelId;
        $this->note = $note;
        $this->userDto = $userDto;
    }

    public function customerId(): string
    {
        return $this->customerId;
    }

    public function lineItemsDto(): LineItemsDto
    {
        return $this->lineItemsDto;
    }

    public function inventoryItemsDto(): InventoryItemsDto
    {
        return $this->inventoryItemsDto;
    }

    public function currency(): string
    {
        return $this->currency;
    }

    public function addressDto(): AddressDto
    {
        return $this->addressDto;
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

    public function userDto(): UserDto
    {
        return $this->userDto;
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data = []): CreateReservation
    {
        return Factory::createReservation($data);
    }
}
