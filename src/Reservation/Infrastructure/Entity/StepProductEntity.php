<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Reservation\Infrastructure\Entity\Embeddable\PriceEmbeddable;

#[Table(name: 'step_products')]
class StepProductEntity
{
    #[Column(type: 'uuid')]
    private string $productId;

    #[Embedded]
    private PriceEmbeddable $price;

    #[Column(type: 'integer')]
    private int $quantity;

    #[ManyToOne(targetEntity: StepEntity::class, inversedBy: 'stepProducts')]
    #[JoinColumn(name: 'step_id', referencedColumnName: 'id')]
    private StepEntity $stepEntity;

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): StepProductEntity
    {
        $this->productId = $productId;

        return $this;
    }

    public function getPrice(): PriceEmbeddable
    {
        return $this->price;
    }

    public function setPrice(PriceEmbeddable $price): StepProductEntity
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): StepProductEntity
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getStepEntity(): StepEntity
    {
        return $this->stepEntity;
    }

    public function setStepEntity(StepEntity $stepEntity): StepProductEntity
    {
        $this->stepEntity = $stepEntity;

        return $this;
    }
}
