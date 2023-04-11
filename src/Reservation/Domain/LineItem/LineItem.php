<?php

declare(strict_types=1);

namespace Reservation\Domain\LineItem;

use Money\Money;
use Reservation\Application\Dto\LineItemDto;
use Reservation\Application\Dto\PriceDto;
use Reservation\Application\Dto\StepsDto;
use Reservation\Domain\Collection\Steps;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Exception\StepNotFound;
use Reservation\Domain\Identity\LineItemId;
use Reservation\Domain\Identity\ProductId;
use Reservation\Domain\Identity\StepId;
use Reservation\Domain\MoneyFactory;

final class LineItem
{
    private LineItemId $lineItemId;
    private ProductId $productId;
    private Money $price;
    private Quantity $quantity;
    private Steps $steps;

    public function __construct(
        LineItemId $lineItemId,
        ProductId $productId,
        Money $price,
        Quantity $quantity,
        Steps $steps
    ) {
        $this->lineItemId = $lineItemId;
        $this->productId = $productId;
        $this->price = $price;
        $this->quantity = $quantity;
        $this->steps = $steps;
    }

    /**
     * @throws InvalidArgument
     */
    public static function create(
        string $lineItemId,
        string $productId,
        PriceDto $price,
        int $quantity,
        StepsDto $stepsDto
    ): LineItem {
        return new LineItem(
            LineItemId::fromString($lineItemId),
            ProductId::fromString($productId),
            MoneyFactory::fromPriceDto($price),
            Quantity::fromInteger($quantity),
            Steps::fromDto($stepsDto)
        );
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromDto(LineItemDto $lineItemDto): LineItem
    {
        return self::create(
            $lineItemDto->lineItemId() ?? (new LineItemId())->toString(),
            $lineItemDto->productId(),
            $lineItemDto->priceDto(),
            $lineItemDto->quantity(),
            $lineItemDto->stepsDto()
        );
    }

    public function lineItemId(): LineItemId
    {
        return $this->lineItemId;
    }

    public function steps(): Steps
    {
        return $this->steps;
    }

    /**
     * @throws StepNotFound
     */
    public function step(StepId $stepId): Step
    {
        foreach ($this->steps as $step) {
            if ($step->stepId()->equals($stepId)) {
                return $step;
            }
        }

        throw new StepNotFound($stepId);
    }

    /**
     * @param array<string> $ids
     */
    public function filterSteps(array $ids): Steps
    {
        $steps = array_filter($this->steps->toArray(), function ($step) use ($ids) {
            return in_array($step->stepId()->toString(), $ids);
        });

        return new Steps(...$steps);
    }

    public function productId(): ProductId
    {
        return $this->productId;
    }

    /**
     * @return string[]
     */
    public function mapStepIds(): array
    {
        return array_map(function ($step) {
            return $step->stepId()->toString();
        }, $this->steps->toArray());
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
