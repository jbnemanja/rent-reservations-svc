<?php

declare(strict_types=1);

namespace Reservation\Domain\LineItem;

use Money\Money;
use Reservation\Application\Dto\StepProductDto;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Identity\ProductId;
use Reservation\Domain\MoneyFactory;

final class StepProduct
{
    private ProductId $productId;
    private Money $price;
    private Quantity $quantity;

    public function __construct(ProductId $productId, Money $price, Quantity $quantity)
    {
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromDto(StepProductDto $stepProductDto): StepProduct
    {
        return new StepProduct(
            ProductId::fromString($stepProductDto->productId()),
            MoneyFactory::fromPriceDto($stepProductDto->price()),
            Quantity::fromInteger($stepProductDto->quantity())
        );
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    public function price(): Money
    {
        return $this->price;
    }

    public function quantity(): Quantity
    {
        return $this->quantity;
    }
}
