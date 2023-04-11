<?php

declare(strict_types=1);

namespace Reservation\Domain\LineItem;

final class Quantity
{
    private int $quantity;

    private function __construct(int $quantity)
    {
        $this->quantity = $quantity;
    }

    public static function fromInteger(int $quantity): Quantity
    {
        return new self($quantity);
    }

    public function toInteger(): int
    {
        return $this->quantity;
    }

    public function equals(self $quantity): bool
    {
        return $this->quantity === $quantity->quantity;
    }
}
