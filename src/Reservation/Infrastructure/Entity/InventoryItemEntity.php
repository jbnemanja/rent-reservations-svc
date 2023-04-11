<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'inventory_items')]
class InventoryItemEntity
{
    //todo moze li bez primarnog kljuca
    #[Column(type: 'uuid')]
    private string $inventoryItemId;

    #[Column(type: 'uuid')]
    private string $productId;

    #[ManyToOne(targetEntity: ReservationEntity::class, inversedBy: 'inventoryItems')]
    #[JoinColumn(name: 'reservation_id', referencedColumnName: 'id')]
    private ReservationEntity $reservation;

    public function getInventoryItemId(): string
    {
        return $this->inventoryItemId;
    }

    public function setInventoryItemId(string $inventoryItemId): InventoryItemEntity
    {
        $this->inventoryItemId = $inventoryItemId;

        return $this;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): InventoryItemEntity
    {
        $this->productId = $productId;

        return $this;
    }

    public function getReservation(): ReservationEntity
    {
        return $this->reservation;
    }

    public function setReservation(ReservationEntity $reservation): InventoryItemEntity
    {
        $this->reservation = $reservation;

        return $this;
    }
}
