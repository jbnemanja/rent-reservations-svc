<?php

declare(strict_types=1);

namespace Reservation\Domain;

enum TimeUnit: string
{
    case Hour = 'hour';
    case Day = 'day';
    case Week = 'week';
    case Month = 'month';
    case Year = 'year';
}
