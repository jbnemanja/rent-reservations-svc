<?php

declare(strict_types=1);

namespace Reservation\Domain\Collection;

use Iterator;
use Reservation\Application\Dto\LineItemDto;
use Reservation\Application\Dto\LineItemsDto;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\LineItem\LineItem;

/**
 * @implements Iterator<int, LineItem>
 */
final class LineItems implements Iterator
{
    /**
     * @var LineItem[]
     */
    private array $lineItems;
    private int $position;

    public function __construct(LineItem ...$lineItems)
    {
        $this->lineItems = $lineItems;
        $this->rewind();
    }

    public static function fromDto(LineItemsDto $lineItemsDto): LineItems
    {
        $lineItems = array_map(/**
         * @throws InvalidArgument
         */ function (LineItemDto $lineItemDto) {
            return LineItem::fromDto($lineItemDto);
        }, $lineItemsDto->toArray());

        return new LineItems(...$lineItems);
    }

    /**
     * @return LineItem[]
     */
    public function toArray(): array
    {
        return $this->lineItems;
    }

    public function current(): LineItem
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
}
