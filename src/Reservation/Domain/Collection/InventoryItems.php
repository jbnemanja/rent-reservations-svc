<?php

declare(strict_types=1);

namespace Reservation\Domain\Collection;

use Iterator;
use Reservation\Application\Dto\InventoryItemDto;
use Reservation\Application\Dto\InventoryItemsDto;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\LineItem\InventoryItem;

/**
 * @implements Iterator<InventoryItem>
 */
final class InventoryItems implements Iterator
{
    /**
     * @var InventoryItem[]
     */
    private array $inventoryItems;
    private int $position;

    public function __construct(InventoryItem ...$inventoryItems)
    {
        $this->inventoryItems = $inventoryItems;
        $this->rewind();
    }

    public static function fromDto(InventoryItemsDto $inventoryItemsDto): InventoryItems
    {
        $inventoryItems = array_map(function (InventoryItemDto $inventoryItemDto) {
            return InventoryItem::fromDto($inventoryItemDto);
        }, $inventoryItemsDto->toArray());

        return new InventoryItems(...$inventoryItems);
    }

    public function current(): InventoryItem
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
     * @return InventoryItem[]
     */
    public function toArray(): array
    {
        return $this->inventoryItems;
    }
}
