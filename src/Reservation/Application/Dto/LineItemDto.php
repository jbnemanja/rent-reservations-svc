<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class LineItemDto
{
    private ?string $lineItemId;
    private string $productId;
    private PriceDto $priceDto;
    private int $quantity;
    private StepsDto $stepsDto;

    public function __construct(
        ?string $lineItemId,
        string $productId,
        PriceDto $priceDto,
        int $quantity,
        StepsDto $stepsDto
    ) {
        $this->lineItemId = $lineItemId;
        $this->productId = $productId;
        $this->priceDto = $priceDto;
        $this->quantity = $quantity;
        $this->stepsDto = $stepsDto;
    }

    public function lineItemId(): ?string
    {
        return $this->lineItemId;
    }

    public function productId(): string
    {
        return $this->productId;
    }

    public function priceDto(): PriceDto
    {
        return $this->priceDto;
    }

    public function quantity(): int
    {
        return $this->quantity;
    }

    public function stepsDto(): StepsDto
    {
        return $this->stepsDto;
    }
}
