<?php

declare(strict_types=1);

namespace Reservation\Domain\LineItem;

use Reservation\Application\Dto\InventoryItemDto;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Identity\InventoryItemId;
use Reservation\Domain\Identity\ProductId;

final class InventoryItem
{
    private InventoryItemId $inventoryItemId;
    private ProductId $productId;

    public function __construct(ProductId $productId, InventoryItemId $inventoryItemId)
    {
        $this->productId = $productId;
        $this->inventoryItemId = $inventoryItemId;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromDto(InventoryItemDto $inventoryItemDto): InventoryItem
    {
        return new InventoryItem(
            ProductId::fromString($inventoryItemDto->productId()),
            InventoryItemId::fromString($inventoryItemDto->inventoryItemId())
        );
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function inventoryItemId(): InventoryItemId
    {
        return $this->inventoryItemId;
    }
}
