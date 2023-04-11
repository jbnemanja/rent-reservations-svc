<?php

declare(strict_types=1);

namespace Reservation\Domain\Exception;

use Exception;
use Reservation\Domain\Identity\ProductId;

final class StepProductNotFound extends Exception implements DomainExceptionInterface
{
    // todo not 404
    private const HTTP_RESPONSE_CODE = '404';

    public function __construct(ProductId $productId)
    {
        parent::__construct(sprintf('Product with ID %s was not found in step', $productId->toString()), (int) self::HTTP_RESPONSE_CODE);
    }

    public function httpResponseCode(): string
    {
        return self::HTTP_RESPONSE_CODE;
    }
}
