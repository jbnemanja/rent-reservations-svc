<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class RentDurationDto
{
    private string $timeUnit;
    private string $duration;

    public function __construct(string $timeUnit, string $duration)
    {
        $this->timeUnit = $timeUnit;
        $this->duration = $duration;
    }

    public function timeUnit(): string
    {
        return $this->timeUnit;
    }

    public function duration(): string
    {
        return $this->duration;
    }
}
