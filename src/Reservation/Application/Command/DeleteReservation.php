<?php

declare(strict_types=1);

namespace Reservation\Application\Command;

use Exception;
use OpenApi\Domain\HasOpenApiSpec;
use Reservation\Application\Dto\UserDto;

final class DeleteReservation implements HasOpenApiSpec
{
    private string $reservationId;
    private UserDto $userDto;

    public function __construct(string $reservationId, UserDto $userDto)
    {
        $this->reservationId = $reservationId;
        $this->userDto = $userDto;
    }

    public function reservationId(): string
    {
        return $this->reservationId;
    }

    public function userDto(): UserDto
    {
        return $this->userDto;
    }

    /**
     * @param array<string, mixed> $data
     * @return DeleteReservation
     * @throws Exception
     */
    public static function fromArray(array $data = []): self
    {
        if (!isset($data['user']) || !is_array($data['user'])) {
            throw new Exception('User missing');
        }

        $userDto = new UserDto(
            (string) $data['user']['id'],
            (string) $data['user']['company_id']
        );

        return new self((string) $data['reservation_id'], $userDto);
    }
}
