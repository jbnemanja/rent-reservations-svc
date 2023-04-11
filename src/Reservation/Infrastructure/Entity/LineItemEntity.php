<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Reservation\Infrastructure\Entity\Embeddable\PriceEmbeddable;

#[Table(name: 'line_items')]
class LineItemEntity
{
    #[Id]
    #[Column(type: 'uuid', unique: true)]
    #[GeneratedValue(strategy: 'NONE')] // because it's set in domain, how does this affect doctrine internals
    private string $lineItemId;

    #[Column(type: 'uuid')]
    private string $productId;

    #[Embedded]
    private PriceEmbeddable $price;

    #[Column(type: 'integer')]
    private int $quantity;

    /**
     * @var Collection<int,StepEntity>
     */
    #[OneToMany(mappedBy: 'lineItem', targetEntity: StepEntity::class, orphanRemoval: true)]
    private Collection $steps;

    #[ManyToOne(targetEntity: ReservationEntity::class, inversedBy: 'lineItems')]
    #[JoinColumn(name: 'reservation_id', referencedColumnName: 'id')]
    private ReservationEntity $reservation;

    public function __construct()
    {
        $this->steps = new ArrayCollection();
    }

    public function getLineItemId(): string
    {
        return $this->lineItemId;
    }

    public function setLineItemId(string $lineItemId): LineItemEntity
    {
        $this->lineItemId = $lineItemId;

        return $this;
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function setProductId(string $productId): LineItemEntity
    {
        $this->productId = $productId;

        return $this;
    }

    public function getPrice(): PriceEmbeddable
    {
        return $this->price;
    }

    public function setPrice(PriceEmbeddable $price): LineItemEntity
    {
        $this->price = $price;

        return $this;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function setQuantity(int $quantity): LineItemEntity
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getReservation(): ReservationEntity
    {
        return $this->reservation;
    }

    public function setReservation(ReservationEntity $reservation): LineItemEntity
    {
        $this->reservation = $reservation;

        return $this;
    }

    /**
     * @return Collection<int,StepEntity>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function getStep(string $stepId): StepEntity|false
    {
        return $this->steps->filter(function (StepEntity $stepEntity) use ($stepId) {
            return $stepEntity->getStepId() === $stepId;
        })->first();
    }

    /**
     * @return array<string>
     */
    public function getStepIds(): array
    {
        return $this->steps->map(function ($stepEntity) {
            return $stepEntity->getStepId();
        })->toArray();
    }

    /**
     * @param Collection<int, StepEntity> $steps
     *
     * @return $this
     */
    public function setSteps(Collection $steps): LineItemEntity
    {
        $this->steps = $steps;

        return $this;
    }
}
