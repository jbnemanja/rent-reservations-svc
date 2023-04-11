<?php

declare(strict_types=1);

namespace Reservation\Domain;

use DateTimeImmutable;
use Reservation\Application\Dto\ArrivalTimeDto;
use Reservation\Domain\Exception\InvalidArrivalTime;

final class ArrivalTime
{
    private ArrivalTimeType $arrivalTimeType;
    private ?DateTimeImmutable $exactlyAt;
    private ?DateTimeImmutable $from;
    private ?DateTimeImmutable $to;

    private function __construct(ArrivalTimeType $arrivalTimeType, ?DateTimeImmutable $exactlyAt, ?DateTimeImmutable $from, ?DateTimeImmutable $to)
    {
        $this->arrivalTimeType = $arrivalTimeType;
        $this->exactlyAt = $exactlyAt;
        $this->from = $from;
        $this->to = $to;
    }

    public function arrivalTimeType(): ArrivalTimeType
    {
        return $this->arrivalTimeType;
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

    public static function exact(DateTimeImmutable $exactlyAt): ArrivalTime
    {
        return new ArrivalTime(ArrivalTimeType::Exact, $exactlyAt, null, null);
    }

    public static function span(DateTimeImmutable $from, DateTimeImmutable $to): ArrivalTime
    {
        return new ArrivalTime(ArrivalTimeType::Span, null, $from, $to);
    }

    /**
     * @throws InvalidArrivalTime
     */
    public static function fromDto(ArrivalTimeDto $arrivalTimeDto): ArrivalTime
    {
        if (null !== $arrivalTimeDto->exactlyAt() && null === $arrivalTimeDto->from() && null === $arrivalTimeDto->to()) {
            return self::exact($arrivalTimeDto->exactlyAt());
        }

        if (null === $arrivalTimeDto->exactlyAt() && null !== $arrivalTimeDto->from() && null !== $arrivalTimeDto->to()) {
            return self::span($arrivalTimeDto->from(), $arrivalTimeDto->to());
        }

        throw new InvalidArrivalTime();
    }
}
