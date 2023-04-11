<?php

declare(strict_types=1);

namespace Reservation\Application\Dto;

final class StepDto
{
    private string $stepId;
    private StepProductsDto $stepProductsDto;

    public function __construct(string $stepId, StepProductsDto $stepProductsDto)
    {
        $this->stepId = $stepId;
        $this->stepProductsDto = $stepProductsDto;
    }

    public function stepId(): string
    {
        return $this->stepId;
    }

    public function stepProductsDto(): StepProductsDto
    {
        return $this->stepProductsDto;
    }
}
