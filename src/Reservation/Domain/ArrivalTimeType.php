<?php

namespace Reservation\Domain;

enum ArrivalTimeType: string
{
    case Exact = 'exact';
    case Span = 'span';
}
