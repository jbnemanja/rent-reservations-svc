<?php

declare(strict_types=1);

namespace Reservation\Domain\Exception;

use Exception;
use Reservation\Domain\Identity\ReservationId;

final class ReservationNotFound extends Exception implements DomainExceptionInterface
{
    private const HTTP_RESPONSE_CODE = '404';

    public function __construct(ReservationId $reservationId)
    {
        parent::__construct(
            sprintf('Reservation with ID %s was not found.', $reservationId->toString()),
            (int) self::HTTP_RESPONSE_CODE
        );
    }

    public function httpResponseCode(): string
    {
        return self::HTTP_RESPONSE_CODE;
    }
}
