<?php

declare(strict_types=1);

namespace Reservation\Domain;

enum ReservationStatus: string
{
    case NotDelivered = 'not_delivered';
    case Delivered = 'delivered';
    case Canceled = 'canceled';
}
