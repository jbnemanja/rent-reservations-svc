<?php

declare(strict_types=1);

namespace Reservation\Domain\LineItem;

use Reservation\Application\Dto\StepDto;
use Reservation\Domain\Collection\StepProducts;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Exception\StepProductNotFound;
use Reservation\Domain\Identity\ProductId;
use Reservation\Domain\Identity\StepId;

final class Step
{
    private StepId $stepId;
    private StepProducts $stepProducts;

    public function __construct(StepId $stepId, StepProducts $stepProducts)
    {
        $this->stepId = $stepId;
        $this->stepProducts = $stepProducts;
    }

    /**
     * @throws InvalidArgument
     */
    public static function fromDto(StepDto $stepDto): self
    {
        return new self(
            StepId::fromString($stepDto->stepId()),
            StepProducts::fromDto($stepDto->stepProductsDto())
        );
    }

    public function stepId(): StepId
    {
        return $this->stepId;
    }

    public function stepProducts(): StepProducts
    {
        return $this->stepProducts;
    }

    /**
     * @throws StepProductNotFound
     */
    public function product(ProductId $productId): StepProduct
    {
        foreach ($this->stepProducts as $stepProduct) {
            if ($stepProduct->productId()->equals($productId)) {
                return $stepProduct;
            }
        }

        throw new StepProductNotFound($productId);
    }

    /**
     * @param string[] $ids
     */
    public function filterStepProducts(array $ids): StepProducts
    {
        $stepProducts = array_filter($this->stepProducts->toArray(), function ($stepProduct) use ($ids) {
            return in_array($stepProduct->productId()->toString(), $ids);
        });

        return new StepProducts(...$stepProducts);
    }

    /**
     * @return array<string>
     */
    public function mapStepProductIds(): array
    {
        return array_map(function ($step) {
            return $step->productId()->toString();
        }, $this->stepProducts->toArray());
    }
}
