<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

use DateTimeImmutable;

final class ArrivalTimeDto
{
    private ?DateTimeImmutable $exactlyAt;
    private ?DateTimeImmutable $from;
    private ?DateTimeImmutable $to;

    public function __construct(?DateTimeImmutable $exactlyAt, ?DateTimeImmutable $from, ?DateTimeImmutable $to)
    {
        $this->exactlyAt = $exactlyAt;
        $this->from = $from;
        $this->to = $to;
    }

    public function exactlyAt(): ?DateTimeImmutable
    {
        return $this->exactlyAt;
    }

    public function from(): ?DateTimeImmutable
    {
        return $this->from;
    }

    public function to(): ?DateTimeImmutable
    {
        return $this->to;
    }
}
