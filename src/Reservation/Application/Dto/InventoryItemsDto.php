<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

use Iterator;

/**
 * @implements Iterator<InventoryItemDto>
 */
final class InventoryItemsDto implements Iterator
{
    /**
     * @var InventoryItemDto[]
     */
    private array $inventoryItems;
    private int $position;

    public function __construct(InventoryItemDto ...$inventoryItems)
    {
        $this->inventoryItems = $inventoryItems;
        $this->rewind();
    }

    public function current(): InventoryItemDto
    {
        return $this->inventoryItems[$this->position];
    }

    public function next(): void
    {
        ++$this->position;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid(): bool
    {
        return array_key_exists($this->position, $this->inventoryItems);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return InventoryItemDto[]
     */
    public function toArray(): array
    {
        return $this->inventoryItems;
    }
}
