<?php

declare(strict_types=1);

namespace Reservation\Domain\Address;

final class AddressCity
{
    private string $addressCity;

    private function __construct(string $addressCity)
    {
        $this->addressCity = $addressCity;
    }

    public static function fromString(string $addressCity): AddressCity
    {
        return new self($addressCity);
    }

    public function toString(): string
    {
        return $this->addressCity;
    }

    public function equals(self $addressCity): bool
    {
        return $this->addressCity === $addressCity->addressCity;
    }
}
