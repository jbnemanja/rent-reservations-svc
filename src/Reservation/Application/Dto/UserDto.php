<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class UserDto
{
    private string $userId;
    private string $companyId;

    public function __construct(string $userId, string $companyId)
    {
        $this->userId = $userId;
        $this->companyId = $companyId;
    }

    public function userId(): string
    {
        return $this->userId;
    }

    public function companyId(): string
    {
        return $this->companyId;
    }
}
