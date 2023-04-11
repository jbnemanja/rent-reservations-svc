<?php

namespace Reservation\Domain\Exception;

interface DomainExceptionInterface
{
    public function httpResponseCode(): string;
}
