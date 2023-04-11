<?php

declare(strict_types=1);

namespace Reservation\Domain\Identity;

use Ramsey\Uuid\Uuid;
use Reservation\Domain\Exception\InvalidArgument;

final class ReservationId
{
    private string $uuid;

    /**
     * @throws InvalidArgument
     */
    public function __construct(?string $uuid = null)
    {
        $uuid = null === $uuid ? Uuid::uuid4()->toString() : $uuid;

        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgument(sprintf("ReservationId '%s' is not valid UUID.", $uuid));
        }

        $this->uuid = $uuid;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromString(string $uuid): ReservationId
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $reservationId): bool
    {
        return $this->uuid === $reservationId->uuid;
    }
}
