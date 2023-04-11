<?php

declare(strict_types=1);

namespace Reservation\Domain\Exception;

use Exception;

final class InvalidArgument extends Exception implements DomainExceptionInterface
{
    private const HTTP_RESPONSE_CODE = '400';

    public function __construct(string $message)
    {
        parent::__construct($message, (int) self::HTTP_RESPONSE_CODE);
    }

    public function httpResponseCode(): string
    {
        return self::HTTP_RESPONSE_CODE;
    }
}
