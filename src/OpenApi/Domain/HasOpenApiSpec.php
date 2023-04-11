<?php

declare(strict_types=1);

namespace OpenApi\Domain;

use OpenApi\Domain\Exception\FailedToCreateObjectFromOpenApiSpec;

interface HasOpenApiSpec
{
    /**
     * @param array<string, mixed> $data
     *
     * @throws FailedToCreateObjectFromOpenApiSpec
     */
    public static function fromArray(array $data = []): object;
}
