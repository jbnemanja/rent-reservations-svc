<?php

declare(strict_types=1);

namespace Reservation\Domain\Exception;

use Exception;

final class InvalidArrivalTime extends Exception implements DomainExceptionInterface
{
    private const HTTP_RESPONSE_CODE = '400';

    public function __construct()
    {
        parent::__construct('Invalid arrival time. Set either \'exact\' field OR both \'to\' and \'from\' fields.', (int) self::HTTP_RESPONSE_CODE);
    }

    public function httpResponseCode(): string
    {
        return self::HTTP_RESPONSE_CODE;
    }
}
