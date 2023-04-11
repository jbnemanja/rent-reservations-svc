<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

use Iterator;

/**
 * @implements Iterator<int, StepDto>
 */
final class StepsDto implements Iterator
{
    /**
     * @var StepDto[]
     */
    private array $steps;
    private int $position;

    public function __construct(StepDto ...$steps)
    {
        $this->steps = $steps;
        $this->rewind();
    }

    public function current(): StepDto
    {
        return $this->steps[$this->position];
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
        return array_key_exists($this->position, $this->steps);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return StepDto[]
     */
    public function toArray(): array
    {
        return $this->steps;
    }
}
