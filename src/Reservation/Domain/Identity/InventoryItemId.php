<?php

declare(strict_types=1);

namespace Reservation\Domain\Identity;

use Ramsey\Uuid\Uuid;
use Reservation\Domain\Exception\InvalidArgument;

final class InventoryItemId
{
    private string $uuid;

    /**
     * @throws InvalidArgument
     */
    private function __construct(string $uuid)
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgument(sprintf("InventoryItemId '%s' is not valid UUID.", $uuid));
        }

        $this->uuid = $uuid;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromString(string $uuid): InventoryItemId
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $inventoryItemId): bool
    {
        return $this->uuid === $inventoryItemId->uuid;
    }
}
