<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class AddressDto
{
    private string $city;
    private string $street;
    private string $streetNumber;
    private ?string $apartmentNumber;
    private ?string $floorNumber;
    private ?string $note;

    public function __construct(
        string $city,
        string $street,
        string $streetNumber,
        ?string $apartmentNumber,
        ?string $floorNumber,
        ?string $note
    ) {
        $this->city = $city;
        $this->street = $street;
        $this->streetNumber = $streetNumber;
        $this->apartmentNumber = $apartmentNumber;
        $this->floorNumber = $floorNumber;
        $this->note = $note;
    }

    public function city(): string
    {
        return $this->city;
    }

    public function street(): string
    {
        return $this->street;
    }

    public function streetNumber(): string
    {
        return $this->streetNumber;
    }

    public function apartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    public function floorNumber(): ?string
    {
        return $this->floorNumber;
    }

    public function note(): ?string
    {
        return $this->note;
    }
}
