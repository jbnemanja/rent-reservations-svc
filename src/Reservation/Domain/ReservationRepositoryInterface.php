<?php

namespace Reservation\Domain;

use Reservation\Domain\Collection\Reservations;
use Reservation\Domain\Identity\ReservationId;

interface ReservationRepositoryInterface
{
    public function find(ReservationId $reservationId): Reservation;

    public function findBy(Criteria $criteria): Reservations;

    public function save(Reservation $reservation): void;

    public function nextIdentity(): ReservationId;
}
