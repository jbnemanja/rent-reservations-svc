<?php

declare(strict_types=1);

namespace Reservation\Application\QueryHandler;

use Reservation\Application\Query\FindReservation;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Identity\ReservationId;
use Reservation\Domain\Reservation;
use Reservation\Domain\ReservationRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FindReservationHandler implements MessageHandlerInterface
{
    private ReservationRepositoryInterface $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @throws InvalidArgument
     */
    public function __invoke(FindReservation $query): Reservation
    {
        // todo can user access this resource
        return $this->reservationRepository->find(ReservationId::fromString($query->reservationId()));
    }
}
