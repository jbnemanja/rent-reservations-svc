<?php

declare(strict_types=1);

namespace Reservation\Domain\Exception;

use Exception;
use Reservation\Domain\Identity\StepId;

final class StepNotFound extends Exception implements DomainExceptionInterface
{
    // todo not 404
    private const HTTP_RESPONSE_CODE = '404';

    public function __construct(StepId $stepId)
    {
        parent::__construct(sprintf('Step with ID %s was not found', $stepId->toString()), (int) self::HTTP_RESPONSE_CODE);
    }

    public function httpResponseCode(): string
    {
        return self::HTTP_RESPONSE_CODE;
    }
}
