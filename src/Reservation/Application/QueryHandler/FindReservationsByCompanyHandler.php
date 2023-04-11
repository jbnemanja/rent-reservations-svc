<?php

declare(strict_types=1);

namespace Reservation\Application\QueryHandler;

use Reservation\Application\Query\FindReservations;
use Reservation\Domain\Collection\Reservations;
use Reservation\Domain\Criteria;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Identity\CompanyId;
use Reservation\Domain\ReservationRepositoryInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class FindReservationsByCompanyHandler implements MessageHandlerInterface
{
    private ReservationRepositoryInterface $reservationRepository;

    public function __construct(ReservationRepositoryInterface $reservationRepository)
    {
        $this->reservationRepository = $reservationRepository;
    }

    /**
     * @throws InvalidArgument
     */
    public function __invoke(FindReservations $query): Reservations
    {
        $criteria = new Criteria(CompanyId::fromString($query->user()->companyId()));

        return $this->reservationRepository->findBy($criteria);
    }
}
