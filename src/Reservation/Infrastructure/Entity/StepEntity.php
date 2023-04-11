<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Table(name: 'steps')]
class StepEntity
{
    #[Column(type: 'uuid')]
    private string $stepId;

    /**
     * @var Collection<int,StepProductEntity>
     */
    #[OneToMany(mappedBy: 'step', targetEntity: StepProductEntity::class, orphanRemoval: true)]
    private Collection $stepProducts;

    #[ManyToOne(targetEntity: LineItemEntity::class, inversedBy: 'steps')]
    #[JoinColumn(name: 'line_item_id', referencedColumnName: 'id')]
    private LineItemEntity $lineItemEntity;

    public function __construct()
    {
        $this->stepProducts = new ArrayCollection();
    }

    public function getStepId(): string
    {
        return $this->stepId;
    }

    public function setStepId(string $stepId): StepEntity
    {
        $this->stepId = $stepId;

        return $this;
    }

    /**
     * @return Collection<int, StepProductEntity>
     */
    public function getStepProducts(): Collection
    {
        return $this->stepProducts;
    }

    public function getStepProduct(string $productId): StepProductEntity|false
    {
        return $this->stepProducts->filter(function (StepProductEntity $stepProductEntity) use ($productId) {
            return $stepProductEntity->getProductId() === $productId;
        })->first();
    }

    /**
     * @return string[]
     */
    public function getStepProductIds(): array
    {
        return $this->stepProducts->map(function ($stepProductEntity) {
            return $stepProductEntity->getProductId();
        })->toArray();
    }

    /**
     * @param Collection<int, StepProductEntity> $stepProducts
     * @return StepEntity
     */
    public function setStepProducts(Collection $stepProducts): StepEntity
    {
        $this->stepProducts = $stepProducts;

        return $this;
    }

    public function getLineItemEntity(): LineItemEntity
    {
        return $this->lineItemEntity;
    }

    public function setLineItemEntity(LineItemEntity $lineItemEntity): StepEntity
    {
        $this->lineItemEntity = $lineItemEntity;

        return $this;
    }
}
