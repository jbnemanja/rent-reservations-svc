<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class StepProductDto
{
    private string $productId;
    private PriceDto $price;
    private int $quantity;

    public function __construct(string $productId, PriceDto $price, int $quantity)
    {
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function price(): PriceDto
    {
        return $this->price;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }
}
