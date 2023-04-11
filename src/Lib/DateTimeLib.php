<?php

declare(strict_types=1);

namespace Lib;

use DateTimeImmutable;
use DateTimeZone;

final class DateTimeLib
{
    /** @noinspection PhpUnhandledExceptionInspection */
    public static function current(): DateTimeImmutable
    {
        return new DateTimeImmutable('now', new DateTimeZone('UTC'));
    }
}
