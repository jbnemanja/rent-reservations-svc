<?php

declare(strict_types=1);

namespace Reservation\Domain\Address;

final class AddressApartmentNumber
{
    private string $addressApartmentNumber;

    private function __construct(string $addressApartmentNumber)
    {
        $this->addressApartmentNumber = $addressApartmentNumber;
    }

    public static function fromString(string $addressApartmentNumber): AddressApartmentNumber
    {
        return new self($addressApartmentNumber);
    }

    public function toString(): string
    {
        return $this->addressApartmentNumber;
    }

    public function equals(self $addressApartmentNumber): bool
    {
        return $this->addressApartmentNumber === $addressApartmentNumber->addressApartmentNumber;
    }
}
