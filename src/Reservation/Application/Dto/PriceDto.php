<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class PriceDto
{
    private string $amount;
    private string $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function amount(): string
    {
        return $this->amount;
    }

    public function currency(): string
    {
        return $this->currency;
    }
}
