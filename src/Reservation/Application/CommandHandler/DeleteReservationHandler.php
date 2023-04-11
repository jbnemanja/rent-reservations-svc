<?php

declare(strict_types=1);

namespace Reservation\Application\CommandHandler;

use Reservation\Application\Command\DeleteReservation;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Exception\ReservationAlreadyDeleted;
use Reservation\Domain\Identity\ReservationId;
use Reservation\Domain\ReservationRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class DeleteReservationHandler implements MessageHandlerInterface
{
    private ReservationRepositoryInterface $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @throws InvalidArgument
     * @throws ReservationAlreadyDeleted
     */
    public function __invoke(DeleteReservation $command): void
    {
        $reservation = $this->reservationRepository->find(ReservationId::fromString($command->reservationId()));

        $reservation->markAsDeleted($command);

        $this->reservationRepository->save($reservation);
    }
}
