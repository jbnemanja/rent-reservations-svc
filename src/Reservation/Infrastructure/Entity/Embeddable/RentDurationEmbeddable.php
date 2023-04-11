<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity\Embeddable;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Reservation\Domain\TimeUnit;

#[Embeddable]
class RentDurationEmbeddable
{
    #[Column(type: 'string', enumType: TimeUnit::class)]
    private string $timeUnit;

    #[Column(type: 'string')]
    private string $duration;

    public function __construct(string $timeUnit, string $duration)
    {
        $this->timeUnit = $timeUnit;
        $this->duration = $duration;
    }

    public function getTimeUnit(): string
    {
        return $this->timeUnit;
    }

    public function setTimeUnit(string $timeUnit): RentDurationEmbeddable
    {
        $this->timeUnit = $timeUnit;

        return $this;
    }

    public function getDuration(): string
    {
        return $this->duration;
    }

    public function setDuration(string $duration): RentDurationEmbeddable
    {
        $this->duration = $duration;

        return $this;
    }
}
