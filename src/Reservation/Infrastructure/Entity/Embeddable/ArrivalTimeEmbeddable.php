<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity\Embeddable;

use DateTimeImmutable;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;
use Reservation\Domain\ArrivalTimeType;

#[Embeddable]
class ArrivalTimeEmbeddable
{
    #[Column(type: 'string', enumType: ArrivalTimeType::class)]
    private string $type;

    #[Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $exactlyAt;

    #[Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $from;

    #[Column(type: 'datetime_immutable')]
    private ?DateTimeImmutable $to;

    public function __construct(string $type, ?DateTimeImmutable $exactlyAt, ?DateTimeImmutable $from, ?DateTimeImmutable $to)
    {
        $this->type = $type;
        $this->exactlyAt = $exactlyAt;
        $this->from = $from;
        $this->to = $to;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType(string $type): ArrivalTimeEmbeddable
    {
        $this->type = $type;

        return $this;
    }

    public function getExactlyAt(): ?DateTimeImmutable
    {
        return $this->exactlyAt;
    }

    public function setExactlyAt(?DateTimeImmutable $exactlyAt): ArrivalTimeEmbeddable
    {
        $this->exactlyAt = $exactlyAt;

        return $this;
    }

    public function getFrom(): ?DateTimeImmutable
    {
        return $this->from;
    }

    public function setFrom(?DateTimeImmutable $from): ArrivalTimeEmbeddable
    {
        $this->from = $from;

        return $this;
    }

    public function getTo(): ?DateTimeImmutable
    {
        return $this->to;
    }

    public function setTo(?DateTimeImmutable $to): ArrivalTimeEmbeddable
    {
        $this->to = $to;

        return $this;
    }
}
