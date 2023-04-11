<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class InventoryItemDto
{
    private string $productId;
    private string $inventoryItemId;

    public function __construct(string $productId, string $inventoryItemId)
    {
        $this->productId = $productId;
        $this->inventoryItemId = $inventoryItemId;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function inventoryItemId(): string
    {
        return $this->inventoryItemId;
    }
}
