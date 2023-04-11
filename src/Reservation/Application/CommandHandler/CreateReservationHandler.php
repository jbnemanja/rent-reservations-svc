<?php

declare(strict_types=1);

namespace Reservation\Application\CommandHandler;

use Exception;
use Lib\DateTimeLib;
use Reservation\Application\Command\CreateReservation;
use Reservation\Domain\Exception\InvalidArrivalTime;
use Reservation\Domain\Reservation;
use Reservation\Domain\ReservationRepositoryInterface;
use Reservation\Domain\ReservationStatus;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class CreateReservationHandler implements MessageHandlerInterface
{
    private ReservationRepositoryInterface $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @throws InvalidArrivalTime
     * @throws Exception
     */
    public function __invoke(CreateReservation $command): void
    {
        $now = DateTimeLib::current();

        $reservation = Reservation::create(
            $this->reservationRepository->nextIdentity()->toString(),
            $command->userDto()->companyId(),
            $command->customerId(),
            $command->lineItemsDto(),
            $command->inventoryItemsDto(),
            $command->currency(),
            $command->addressDto(),
            $command->rentDurationDto(),
            $command->toBeDeliveredAt(),
            $command->toBePickedUpAt(),
            $command->toBeDeliveredBy(),
            $command->toBePickedUpBy(),
            ReservationStatus::NotDelivered->value,
            $command->saleChannelId(),
            $command->note(),
            $command->userDto()->userId(),
            $now,
            $command->userDto()->userId(),
            $now,
            null,
            null
        );

        $this->reservationRepository->save($reservation);
    }
}
