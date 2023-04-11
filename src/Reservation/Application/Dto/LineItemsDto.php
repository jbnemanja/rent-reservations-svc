<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

use Iterator;

/**
 * @implements Iterator<int, LineItemDto>
 */
final class LineItemsDto implements Iterator
{
    /**
     * @var LineItemDto[]
     */
    private array $lineItems;
    private int $position;

    public function __construct(LineItemDto ...$lineItems)
    {
        $this->lineItems = $lineItems;
        $this->rewind();
    }

    public function current(): LineItemDto
    {
        return $this->lineItems[$this->position];
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
        return array_key_exists($this->position, $this->lineItems);
    }

    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * @return LineItemDto[]
     */
    public function toArray(): array
    {
        return $this->lineItems;
    }
}
