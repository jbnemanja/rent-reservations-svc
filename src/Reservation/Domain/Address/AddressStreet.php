<?php

declare(strict_types=1);

namespace Reservation\Domain\Address;

final class AddressStreet
{
    private string $addressStreet;

    private function __construct(string $addressStreet)
    {
        $this->addressStreet = $addressStreet;
    }

    public static function fromString(string $addressStreet): AddressStreet
    {
        return new self($addressStreet);
    }

    public function toString(): string
    {
        return $this->addressStreet;
    }

    public function equals(self $addressStreet): bool
    {
        return $this->addressStreet === $addressStreet->addressStreet;
    }
}
