<?php

declare(strict_types=1);

namespace Reservation\Domain\Address;

use Reservation\Application\Dto\AddressDto;

final class Address
{
    private AddressCity $addressCity;
    private AddressStreet $addressStreet;
    private AddressStreetNumber $addressStreetNumber;
    private ?AddressApartmentNumber $addressApartmentNumber;
    private ?AddressFloorNumber $addressFloorNumber;
    private ?AddressNote $addressNote;

    public function __construct(
        AddressCity $addressCity,
        AddressStreet $addressStreet,
        AddressStreetNumber $addressStreetNumber,
        ?AddressApartmentNumber $addressApartmentNumber,
        ?AddressFloorNumber $addressFloorNumber,
        ?AddressNote $addressNote
    ) {
        $this->addressCity = $addressCity;
        $this->addressStreet = $addressStreet;
        $this->addressStreetNumber = $addressStreetNumber;
        $this->addressApartmentNumber = $addressApartmentNumber;
        $this->addressFloorNumber = $addressFloorNumber;
        $this->addressNote = $addressNote;
    }

    public static function fromDto(AddressDto $addressDto): self
    {
        return new self(
            AddressCity::fromString($addressDto->city()),
            AddressStreet::fromString($addressDto->street()),
            AddressStreetNumber::fromString($addressDto->streetNumber()),
            null !== $addressDto->apartmentNumber() ? AddressApartmentNumber::fromString($addressDto->apartmentNumber()) : null,
            null !== $addressDto->floorNumber() ? AddressFloorNumber::fromString($addressDto->floorNumber()) : null,
            null !== $addressDto->note() ? AddressNote::fromString($addressDto->note()) : null,
        );
    }

    public function addressCity(): AddressCity
    {
        return $this->addressCity;
    }

    public function addressStreet(): AddressStreet
    {
        return $this->addressStreet;
    }

    public function addressStreetNumber(): AddressStreetNumber
    {
        return $this->addressStreetNumber;
    }

    public function addressApartmentNumber(): ?AddressApartmentNumber
    {
        return $this->addressApartmentNumber;
    }

    public function addressFloorNumber(): ?AddressFloorNumber
    {
        return $this->addressFloorNumber;
    }

    public function addressNote(): ?AddressNote
    {
        return $this->addressNote;
    }
}
