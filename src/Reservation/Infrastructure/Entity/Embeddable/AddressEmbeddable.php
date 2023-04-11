<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity\Embeddable;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class AddressEmbeddable
{
    #[Column(type: 'string')]
    private string $city;

    #[Column(type: 'string')]
    private string $street;

    #[Column(type: 'string')]
    private string $streetNumber;

    #[Column(type: 'string', nullable: true)]
    private ?string $apartmentNumber;

    #[Column(type: 'string', nullable: true)]
    private ?string $floorNumber;

    #[Column(type: 'string', nullable: true)]
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

    public function getCity(): string
    {
        return $this->city;
    }

    public function setCity(string $city): AddressEmbeddable
    {
        $this->city = $city;

        return $this;
    }

    public function getStreet(): string
    {
        return $this->street;
    }

    public function setStreet(string $street): AddressEmbeddable
    {
        $this->street = $street;

        return $this;
    }

    public function getStreetNumber(): string
    {
        return $this->streetNumber;
    }

    public function setStreetNumber(string $streetNumber): AddressEmbeddable
    {
        $this->streetNumber = $streetNumber;

        return $this;
    }

    public function getApartmentNumber(): ?string
    {
        return $this->apartmentNumber;
    }

    public function setApartmentNumber(?string $apartmentNumber): AddressEmbeddable
    {
        $this->apartmentNumber = $apartmentNumber;

        return $this;
    }

    public function getFloorNumber(): ?string
    {
        return $this->floorNumber;
    }

    public function setFloorNumber(?string $floorNumber): AddressEmbeddable
    {
        $this->floorNumber = $floorNumber;

        return $this;
    }

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): AddressEmbeddable
    {
        $this->note = $note;

        return $this;
    }
}
