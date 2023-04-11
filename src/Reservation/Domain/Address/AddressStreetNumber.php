<?php

declare(strict_types=1);

namespace Reservation\Domain\Address;

final class AddressStreetNumber
{
    private string $addressStreetNumber;

    private function __construct(string $addressStreetNumber)
    {
        $this->addressStreetNumber = $addressStreetNumber;
    }

    public static function fromString(string $addressStreetNumber): AddressStreetNumber
    {
        return new self($addressStreetNumber);
    }

    public function toString(): string
    {
        return $this->addressStreetNumber;
    }

    public function equals(self $addressStreetNumber): bool
    {
        return $this->addressStreetNumber === $addressStreetNumber->addressStreetNumber;
    }
}
