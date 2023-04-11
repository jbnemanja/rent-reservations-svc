<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Mapper;

use Money\Money;
use Reservation\Domain\Address\Address;
use Reservation\Domain\ArrivalTime;
use Reservation\Domain\RentDuration;
use Reservation\Infrastructure\Entity\Embeddable\AddressEmbeddable;
use Reservation\Infrastructure\Entity\Embeddable\ArrivalTimeEmbeddable;
use Reservation\Infrastructure\Entity\Embeddable\PriceEmbeddable;
use Reservation\Infrastructure\Entity\Embeddable\RentDurationEmbeddable;

final class FromDomainMapper
{
    public function fromArrivalTime(ArrivalTime $arrivalTime): ArrivalTimeEmbeddable
    {
        return new ArrivalTimeEmbeddable(
            $arrivalTime->arrivalTimeType()->value,
            $arrivalTime->exactlyAt(),
            $arrivalTime->from(),
            $arrivalTime->to()
        );
    }

    public function fromAddress(Address $address): AddressEmbeddable
    {
        return new AddressEmbeddable(
            $address->addressCity()->toString(),
            $address->addressStreet()->toString(),
            $address->addressStreetNumber()->toString(),
            $address->addressApartmentNumber()?->toString(),
            $address->addressFloorNumber()?->toString(),
            $address->addressNote()?->toString()
        );
    }

    public function fromRentDuration(RentDuration $rentDuration): RentDurationEmbeddable
    {
        return new RentDurationEmbeddable(
            $rentDuration->timeUnit()->value,
            $rentDuration->duration()
        );
    }

    public function fromPrice(Money $price): PriceEmbeddable
    {
        return new PriceEmbeddable(
            $price->getAmount(),
            $price->getCurrency()->getCode()
        );
    }
}
