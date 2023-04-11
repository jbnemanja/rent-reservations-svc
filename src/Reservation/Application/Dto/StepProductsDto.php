<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

use Iterator;

/**
 * @implements Iterator<StepProductDto>
 */
final class StepProductsDto implements Iterator
{
    /**
     * @var StepProductDto[]
     */
    private array $stepProducts;
    private int $position;

    public function __construct(StepProductDto ...$stepProducts)
    {
        $this->stepProducts = $stepProducts;
        $this->rewind();
    }

    public function current(): StepProductDto
    {
        return $this->stepProducts[$this->position];
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
        return array_key_exists($this->position, $this->stepProducts);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return StepProductDto[]
     */
    public function toArray(): array
    {
        return $this->stepProducts;
    }
}
