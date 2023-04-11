<?php

declare(strict_types=1);

namespace Reservation\Domain\Identity;

use Ramsey\Uuid\Uuid;
use Reservation\Domain\Exception\InvalidArgument;

final class StepProductId
{
    private string $uuid;

    /**
     * @throws InvalidArgument
     */
    private function __construct(string $uuid)
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgument(sprintf("StepProductId '%s' is not valid UUID.", $uuid));
        }

        $this->uuid = $uuid;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromString(string $uuid): StepProductId
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $stepProductId): bool
    {
        return $this->uuid === $stepProductId->uuid;
    }
}
