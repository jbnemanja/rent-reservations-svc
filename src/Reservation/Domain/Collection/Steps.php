<?php

declare(strict_types=1);

namespace Reservation\Domain\Collection;

use Iterator;
use Reservation\Application\Dto\StepDto;
use Reservation\Application\Dto\StepsDto;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\LineItem\Step;

/**
 * @implements Iterator<int, Step>
 */
final class Steps implements Iterator
{
    /**
     * @var Step[]
     */
    private array $steps;
    private int $position;

    public function __construct(Step ...$steps)
    {
        $this->steps = $steps;
        $this->rewind();
    }

    public static function fromDto(StepsDto $stepsDto): Steps
    {
        $steps = array_map(/**
         * @throws InvalidArgument
         */ function (StepDto $stepDto) {
            return Step::fromDto($stepDto);
        }, $stepsDto->toArray());

        return new Steps(...$steps);
    }

    public function current(): Step
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
     * @return Step[]
     */
    public function toArray(): array
    {
        return $this->steps;
    }
}
