<?php

declare(strict_types=1);

namespace Reservation\Domain\Exception;

use Exception;
use Reservation\Domain\Identity\LineItemId;

final class LineItemNotFound extends Exception implements DomainExceptionInterface
{
    private const HTTP_RESPONSE_CODE = '404';

    public function __construct(LineItemId $lineItemId)
    {
        parent::__construct(sprintf('Line item with ID %s was not found', $lineItemId->toString()), (int) self::HTTP_RESPONSE_CODE);
    }

    public function httpResponseCode(): string
    {
        return self::HTTP_RESPONSE_CODE;
    }
}
