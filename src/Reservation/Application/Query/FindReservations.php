<?php

declare(strict_types=1);

namespace Reservation\Application\Query;

use Exception;
use OpenApi\Domain\HasOpenApiSpec;
use Reservation\Application\Dto\UserDto;

final class FindReservations implements HasOpenApiSpec
{
    private UserDto $user;

    public function __construct(UserDto $user)
    {
        $this->user = $user;
    }

    public function user(): UserDto
    {
        return $this->user;
    }

    /**
     * @throws Exception
     */
    public static function fromArray(array $data = []): FindReservations
    {
        if (!isset($data['user']) || !is_array($data['user'])) {
            throw new Exception('User missing');
        }

        return new FindReservations(new UserDto((string) $data['user']['id'], (string) $data['user']['company_id']));
    }
}
