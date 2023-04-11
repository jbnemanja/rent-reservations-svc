<?php

declare(strict_types=1);

namespace Reservation\Application\Event;

use Exception;
use Reservation\Application\Dto\UserDto;

// todo how do we go on about subscribing to these events because they are internal and not part of open api spec
final class ReservationDelivered
{
    private UserDto $userDto;
    private string $reservationId;

    public function __construct(UserDto $userDto, string $reservationId)
    {
        $this->userDto = $userDto;
        $this->reservationId = $reservationId;
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
     * @return static
     * @throws Exception
     */
    public static function fromArray(array $data = []): self
    {
        if (!isset($data['user']) || !is_array($data['user'])) {
            throw new Exception('User missing');
        }

        return new ReservationDelivered(
            new UserDto(
                (string) $data['user']['id'],
                (string) $data['user']['company_id']
            ),
            (string) $data['reservation_id']
        );
    }
}
