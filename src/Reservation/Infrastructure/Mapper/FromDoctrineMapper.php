<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Mapper;

use Reservation\Application\Dto\AddressDto;
use Reservation\Application\Dto\ArrivalTimeDto;
use Reservation\Application\Dto\InventoryItemDto;
use Reservation\Application\Dto\InventoryItemsDto;
use Reservation\Application\Dto\LineItemDto;
use Reservation\Application\Dto\LineItemsDto;
use Reservation\Application\Dto\PriceDto;
use Reservation\Application\Dto\RentDurationDto;
use Reservation\Application\Dto\StepDto;
use Reservation\Application\Dto\StepProductDto;
use Reservation\Application\Dto\StepProductsDto;
use Reservation\Application\Dto\StepsDto;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Exception\InvalidArrivalTime;
use Reservation\Domain\Reservation;
use Reservation\Infrastructure\Entity\Embeddable\AddressEmbeddable;
use Reservation\Infrastructure\Entity\Embeddable\ArrivalTimeEmbeddable;
use Reservation\Infrastructure\Entity\Embeddable\RentDurationEmbeddable;
use Reservation\Infrastructure\Entity\InventoryItemEntity;
use Reservation\Infrastructure\Entity\LineItemEntity;
use Reservation\Infrastructure\Entity\ReservationEntity;
use Reservation\Infrastructure\Entity\StepEntity;
use Reservation\Infrastructure\Entity\StepProductEntity;

final class FromDoctrineMapper
{
    /**
     * @throws InvalidArgument
     * @throws InvalidArrivalTime
     */
    public function fromReservation(ReservationEntity $reservationEntity): Reservation
    {
        $lineItems = [];
        foreach ($reservationEntity->getLineItems() as $lineItem) {
            $lineItems[] = $this->fromLineItem($lineItem);
        }

        $inventoryItems = [];
        foreach ($reservationEntity->getInventoryItems() as $inventoryItem) {
            $inventoryItems[] = $this->fromInventoryItem($inventoryItem);
        }

        return Reservation::create(
            $reservationEntity->getReservationId(),
            $reservationEntity->getCompanyId(),
            $reservationEntity->getCustomerId(),
            new LineItemsDto(...$lineItems),
            new InventoryItemsDto(...$inventoryItems),
            $reservationEntity->getCurrency(),
            $this->fromAddress($reservationEntity->getAddress()),
            $this->fromRentDuration($reservationEntity->getRentDuration()),
            $this->fromArrivalTime($reservationEntity->getToBeDeliveredAt()),
            $this->fromArrivalTime($reservationEntity->getToBePickedUpAt()),
            $reservationEntity->getToBeDeliveredBy(),
            $reservationEntity->getToBePickedUpBy(),
            $reservationEntity->getReservationStatus(),
            $reservationEntity->getSaleChannelId(),
            $reservationEntity->getNote(),
            $reservationEntity->getCreatedBy(),
            $reservationEntity->getCreatedAt(),
            $reservationEntity->getModifiedBy(),
            $reservationEntity->getModifiedAt(),
            $reservationEntity->getDeletedBy(),
            $reservationEntity->getDeletedAt()
        );
    }

    private function fromAddress(AddressEmbeddable $addressEmbeddable): AddressDto
    {
        return new AddressDto(
            $addressEmbeddable->getCity(),
            $addressEmbeddable->getStreet(),
            $addressEmbeddable->getStreetNumber(),
            $addressEmbeddable->getApartmentNumber(),
            $addressEmbeddable->getFloorNumber(),
            $addressEmbeddable->getNote()
        );
    }

    private function fromArrivalTime(ArrivalTimeEmbeddable $arrivalTimeEmbeddable): ArrivalTimeDto
    {
        return new ArrivalTimeDto(
            $arrivalTimeEmbeddable->getExactlyAt(),
            $arrivalTimeEmbeddable->getFrom(),
            $arrivalTimeEmbeddable->getTo()
        );
    }

    private function fromRentDuration(RentDurationEmbeddable $rentDurationEmbeddable): RentDurationDto
    {
        return new RentDurationDto(
            $rentDurationEmbeddable->getTimeUnit(),
            $rentDurationEmbeddable->getDuration()
        );
    }

    private function fromLineItem(LineItemEntity $lineItemEntity): LineItemDto
    {
        $steps = [];
        foreach ($lineItemEntity->getSteps() as $step) {
            $steps[] = $this->fromStep($step);
        }

        return new LineItemDto(
            $lineItemEntity->getLineItemId(),
            $lineItemEntity->getProductId(),
            new PriceDto(
                $lineItemEntity->getPrice()->getAmount(),
                $lineItemEntity->getPrice()->getCurrency()
            ),
            $lineItemEntity->getQuantity(),
            new StepsDto(...$steps)
        );
    }

    private function fromStep(StepEntity $stepEntity): StepDto
    {
        $stepProducts = [];
        foreach ($stepEntity->getStepProducts() as $stepProduct) {
            $stepProducts[] = $this->fromStepProduct($stepProduct);
        }

        return new StepDto($stepEntity->getStepId(), new StepProductsDto(...$stepProducts));
    }

    private function fromStepProduct(StepProductEntity $stepProductEntity): StepProductDto
    {
        return new StepProductDto(
            $stepProductEntity->getProductId(),
            new PriceDto(
                $stepProductEntity->getPrice()->getAmount(),
                $stepProductEntity->getPrice()->getCurrency()
            ),
            $stepProductEntity->getQuantity()
        );
    }

    private function fromInventoryItem(InventoryItemEntity $inventoryItemEntity): InventoryItemDto
    {
        return new InventoryItemDto(
            $inventoryItemEntity->getProductId(),
            $inventoryItemEntity->getInventoryItemId()
        );
    }
}
