<?php

declare(strict_types=1);

namespace Reservation\Domain\Identity;

use Ramsey\Uuid\Uuid;
use Reservation\Domain\Exception\InvalidArgument;

final class StepId
{
    private string $uuid;

    /**
     * @throws InvalidArgument
     */
    private function __construct(string $uuid)
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgument(sprintf("StepId '%s' is not valid UUID.", $uuid));
        }

        $this->uuid = $uuid;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromString(string $uuid): StepId
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $stepId): bool
    {
        return $this->uuid === $stepId->uuid;
    }
}
