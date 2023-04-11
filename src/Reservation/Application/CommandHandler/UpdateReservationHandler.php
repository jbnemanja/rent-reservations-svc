<?php

declare(strict_types=1);

namespace Reservation\Application\CommandHandler;

use Reservation\Application\Command\UpdateReservation;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Exception\InvalidArrivalTime;
use Reservation\Domain\Identity\ReservationId;
use Reservation\Domain\ReservationRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class UpdateReservationHandler implements MessageHandlerInterface
{
    private ReservationRepositoryInterface $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @throws InvalidArgument
     * @throws InvalidArrivalTime
     */
    public function __invoke(UpdateReservation $command): void
    {
        $reservation = $this->reservationRepository->find(ReservationId::fromString($command->reservationId()));

        $reservation->withUpdateReservation($command);

        $this->reservationRepository->save($reservation);
    }
}
