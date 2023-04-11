<?php

declare(strict_types=1);

namespace Reservation\Application\Query;

use OpenApi\Domain\HasOpenApiSpec;

final class FindReservation implements HasOpenApiSpec
{
    private string $reservationId;

    public function __construct(string $reservationId)
    {
        $this->reservationId = $reservationId;
    }

    public function reservationId(): string
    {
        return $this->reservationId;
    }

    public static function fromArray(array $data = []): self
    {
        // todo check if user can access this resource ?
        return new self((string) $data['reservation_id']);
    }
}
