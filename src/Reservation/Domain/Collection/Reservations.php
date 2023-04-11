<?php

declare(strict_types=1);

namespace Reservation\Domain\Collection;

use Iterator;
use Reservation\Domain\Reservation;

/**
 * @implements Iterator<int, Reservation>
 */
final class Reservations implements Iterator
{
    /**
     * @var Reservation[]
     */
    private array $reservations;
    private int $position;

    public function __construct(Reservation ...$reservations)
    {
        $this->reservations = $reservations;
        $this->rewind();
    }

    public function current(): Reservation
    {
        return $this->reservations[$this->position];
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
        return array_key_exists($this->position, $this->reservations);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }
}
