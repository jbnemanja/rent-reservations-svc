<?php

declare(strict_types=1);

namespace Reservation\Domain;

use Money\Currency;
use Money\Money;
use Reservation\Application\Dto\PriceDto;
use Reservation\Domain\Exception\InvalidArgument;

final class MoneyFactory
{
    /**
     * @throws InvalidArgument
     */
    public static function fromPriceDto(PriceDto $priceDto): Money
    {
        return new Money((int) $priceDto->amount(), self::currency($priceDto->currency()));
    }

    /**
     * @throws InvalidArgument
     */
    public static function currency(string $currency): Currency
    {
        if (empty($currency) || 3 !== strlen($currency)) {
            throw new InvalidArgument('Currency code should have 3 characters.');
        }

        return new Currency($currency);
    }
}
