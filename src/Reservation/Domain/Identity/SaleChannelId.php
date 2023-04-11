<?php

declare(strict_types=1);

namespace Reservation\Domain\Identity;

// sale channel mora biti vezan kompaniju, eventualno i item/kategoriju
use Ramsey\Uuid\Uuid;
use Reservation\Domain\Exception\InvalidArgument;

final class SaleChannelId
{
    private string $uuid;

    /**
     * @throws InvalidArgument
     */
    private function __construct(string $uuid)
    {
        if (!Uuid::isValid($uuid)) {
            throw new InvalidArgument(sprintf("SaleChannelId '%s' is not valid UUID.", $uuid));
        }

        $this->uuid = $uuid;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromString(string $uuid): SaleChannelId
    {
        return new self($uuid);
    }

    public function toString(): string
    {
        return $this->uuid;
    }

    public function equals(self $saleChannelId): bool
    {
        return $this->uuid === $saleChannelId->uuid;
    }
}
