<?php

declare(strict_types=1);

namespace Reservation\Domain\Exception;

use Exception;
use Reservation\Domain\Identity\ReservationId;

final class ReservationAlreadyDeleted extends Exception implements DomainExceptionInterface
{
    private const HTTP_RESPONSE_CODE = '400';

    public function __construct(ReservationId $reservationId)
    {
        parent::__construct(
            sprintf('Reservation with ID %s was already deleted.', $reservationId->toString()),
            (int) self::HTTP_RESPONSE_CODE
        );
    }

    public function httpResponseCode(): string
    {
        return self::HTTP_RESPONSE_CODE;
    }
}
