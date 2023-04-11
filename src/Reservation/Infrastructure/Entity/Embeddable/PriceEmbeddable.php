<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity\Embeddable;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class PriceEmbeddable
{
    #[Column(type: 'string')]
    private string $amount;

    #[Column(type: 'string', length: 3)]
    private string $currency;

    public function __construct(string $amount, string $currency)
    {
        $this->amount = $amount;
        $this->currency = $currency;
    }

    public function getAmount(): string
    {
        return $this->amount;
    }

    public function setAmount(string $amount): PriceEmbeddable
    {
        $this->amount = $amount;

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): PriceEmbeddable
    {
        $this->currency = $currency;

        return $this;
    }
}
