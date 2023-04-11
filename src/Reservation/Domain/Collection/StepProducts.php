<?php

declare(strict_types=1);

namespace Reservation\Domain\Collection;

use Iterator;
use Reservation\Application\Dto\StepProductDto;
use Reservation\Application\Dto\StepProductsDto;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\LineItem\StepProduct;

/**
 * @implements Iterator<StepProduct>
 */
final class StepProducts implements Iterator
{
    /**
     * @var StepProduct[]
     */
    private array $stepProducts;
    private int $position;

    public function __construct(StepProduct ...$stepProducts)
    {
        $this->stepProducts = $stepProducts;
        $this->rewind();
    }

    public static function fromDto(StepProductsDto $stepProductsDto): StepProducts
    {
        $stepProducts = array_map(/**
         * @throws InvalidArgument
         */ function (StepProductDto $stepProductDto) {
            return StepProduct::fromDto($stepProductDto);
        }, $stepProductsDto->toArray());

        return new StepProducts(...$stepProducts);
    }

    public function current(): StepProduct
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
     * @return StepProduct[]
     */
    public function toArray(): array
    {
        return $this->stepProducts;
    }
}
