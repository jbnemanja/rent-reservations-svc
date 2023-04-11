<?php

declare(strict_types=1);

namespace Reservation\Domain;

use Reservation\Application\Dto\RentDurationDto;

final class RentDuration
{
    private TimeUnit $timeUnit;
    private string $duration;

    // todo ne moze bilo kakav string u duraiton
    public function __construct(TimeUnit $timeUnit, string $duration)
    {
        $this->timeUnit = $timeUnit;
        $this->duration = $duration;
    }

    public function timeUnit(): TimeUnit
    {
        return $this->timeUnit;
    }

    public function duration(): string
    {
        return $this->duration;
    }

    public static function fromDto(RentDurationDto $rentDurationDto): self
    {
        return new self(
            TimeUnit::from($rentDurationDto->timeUnit()),
            $rentDurationDto->duration()
        );
    }
}
