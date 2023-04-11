<?php

declare(strict_types=1);

namespace Reservation\Domain\Address;

final class AddressFloorNumber
{
    private string $addressFloorNumber;

    private function __construct(string $addressFloorNumber)
    {
        $this->addressFloorNumber = $addressFloorNumber;
    }

    public static function fromString(string $addressFloorNumber): AddressFloorNumber
    {
        return new self($addressFloorNumber);
    }

    public function toString(): string
    {
        return $this->addressFloorNumber;
    }

    public function equals(self $addressFloorNumber): bool
    {
        return $this->addressFloorNumber === $addressFloorNumber->addressFloorNumber;
    }
}
