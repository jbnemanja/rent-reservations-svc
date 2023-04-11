<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Reservation\Domain\Collection\InventoryItems;
use Reservation\Domain\Collection\LineItems;
use Reservation\Domain\Collection\Reservations;
use Reservation\Domain\Collection\StepProducts;
use Reservation\Domain\Collection\Steps;
use Reservation\Domain\Criteria;
use Reservation\Domain\Exception\InvalidArgument;
use Reservation\Domain\Exception\InvalidArrivalTime;
use Reservation\Domain\Exception\ReservationNotFound;
use Reservation\Domain\Identity\ReservationId;
use Reservation\Domain\Reservation;
use Reservation\Domain\ReservationRepositoryInterface;
use Reservation\Infrastructure\Entity\InventoryItemEntity;
use Reservation\Infrastructure\Entity\LineItemEntity;
use Reservation\Infrastructure\Entity\ReservationEntity;
use Reservation\Infrastructure\Entity\StepEntity;
use Reservation\Infrastructure\Entity\StepProductEntity;
use Reservation\Infrastructure\Mapper\FromDoctrineMapper;
use Reservation\Infrastructure\Mapper\FromDomainMapper;
use function array_diff;
use function array_intersect;

final class ReservationRepository implements ReservationRepositoryInterface
{
    private EntityManagerInterface $entityManager;
    private FromDomainMapper $fromDomainMapper;
    private FromDoctrineMapper $fromDoctrineMapper;

    public function __construct(EntityManagerInterface $entityManager, FromDomainMapper $fromDomainMapper, FromDoctrineMapper $fromDoctrineMapper)
    {
        $this->entityManager = $entityManager;
        $this->fromDomainMapper = $fromDomainMapper;
        $this->fromDoctrineMapper = $fromDoctrineMapper;
    }

    /**
     * @throws InvalidArgument
     * @throws InvalidArrivalTime
     * @throws ReservationNotFound
     */
    public function find(ReservationId $reservationId): Reservation
    {
        // todo do we need to check for the resource
        $reservationEntity = $this->entityManager->find(ReservationEntity::class, $reservationId->toString());

        if (null === $reservationEntity) {
            throw new ReservationNotFound($reservationId);
        }

        return $this->fromDoctrineMapper->fromReservation($reservationEntity);
    }

    /**
     * @throws InvalidArgument
     * @throws InvalidArrivalTime
     */
    public function findBy(Criteria $criteria): Reservations
    {
        $reservationEntities = $this->entityManager->getRepository(ReservationEntity::class)->findBy([
            'companyId' => $criteria->companyId()->toString(),
        ]);

        $reservations = array_map(function ($reservationEntity) {
            return $this->fromDoctrineMapper->fromReservation($reservationEntity);
        }, $reservationEntities);

        return new Reservations(...$reservations);
    }

    public function save(Reservation $reservation): void
    {
        $reservationEntity = $this->fromReservation($reservation);

        $this->entityManager->persist($reservationEntity);
        $this->entityManager->flush();
    }

    public function nextIdentity(): ReservationId
    {
        return new ReservationId();
    }

    /**
     * @throws \Exception
     */
    private function fromReservation(Reservation $reservation): ReservationEntity
    {
        $reservationEntity = $this->fetchOrCreateReservationEntity($reservation);

        list($lineItemsToRemove, $lineItemsToAdd, $lineItemsToUpdate) = $this->mapIds(
            $reservationEntity->getLineItemIds(),
            $reservation->mapLineItemIds()
        );

        $this->removeLineItemsFromEntity($reservationEntity, $lineItemsToRemove);
        $this->addLineItemsToEntity($reservationEntity, $reservation->filterLineItems($lineItemsToAdd));
        $this->updateLineItemsInEntity($reservationEntity, $reservation->filterLineItems($lineItemsToUpdate));

        list($inventoryItemsToRemove, $inventoryItemsToAdd, $inventoryItemsToUpdate) = $this->mapIds(
            $reservationEntity->getInventoryItemIds(),
            $reservation->mapInventoryItemIds()
        );

        $this->removeInventoryItemsFromEntity($reservationEntity, $inventoryItemsToRemove);
        $this->addInventoryItemsToEntity($reservationEntity, $reservation->filterInventoryItems($inventoryItemsToAdd));
        $this->updateInventoryItemsInEntity($reservationEntity, $reservation->filterInventoryItems($inventoryItemsToUpdate));

        $reservationEntity
            ->setCompanyId($reservation->companyId()->toString())
            ->setCustomerId($reservation->customerId()->toString())
            ->setAddress($this->fromDomainMapper->fromAddress($reservation->address()))
            ->setCurrency($reservation->currency()->getCode())
            ->setRentDuration($this->fromDomainMapper->fromRentDuration($reservation->rentDuration()))
            ->setNote($reservation->note()->toString())
            ->setToBeDeliveredAt($this->fromDomainMapper->fromArrivalTime($reservation->toBeDeliveredAt()))
            ->setToBePickedUpAt($this->fromDomainMapper->fromArrivalTime($reservation->toBePickedUpAt()))
            ->setToBeDeliveredBy($reservation->toBeDeliveredBy()->toString())
            ->setToBePickedUpBy($reservation->toBePickedUpBy()->toString())
            ->setSaleChannelId($reservation->saleChannelId()->toString())
            ->setReservationStatus($reservation->reservationStatus()->value)
            ->setCreatedBy($reservation->createdBy()->toString())
            ->setCreatedAt($reservation->createdAt())
            ->setModifiedBy($reservation->modifiedBy()->toString())
            ->setModifiedAt($reservation->modifiedAt())
            ->setDeletedBy($reservation->deletedBy()?->toString())
            ->setDeletedAt($reservation->deletedAt());

        return $reservationEntity;
    }

    private function fetchOrCreateReservationEntity(Reservation $reservation): ReservationEntity
    {
        $reservationEntity = $this->entityManager->getRepository(ReservationEntity::class)->findOneBy([
            'reservationId' => $reservation->reservationId()->toString(),
            'companyId' => $reservation->companyId()->toString(),
        ]);

        if (null === $reservationEntity) {
            $reservationEntity = new ReservationEntity();
            $reservationEntity->setReservationId($reservation->reservationId()->toString());
        }

        return $reservationEntity;
    }

    /**
     * @param ReservationEntity $reservationEntity
     * @param array<string> $lineItemsToRemove
     * @throws \Exception
     */
    private function removeLineItemsFromEntity(ReservationEntity $reservationEntity, array $lineItemsToRemove): void
    {
        foreach ($lineItemsToRemove as $lineItemIdToRemove) {
            $lineItemEntityToRemove = $reservationEntity->getLineItem($lineItemIdToRemove);

            $this->entityManager->remove($lineItemEntityToRemove);
        }
    }

    private function addLineItemsToEntity(ReservationEntity $reservationEntity, LineItems $lineItems): void
    {
        foreach ($lineItems as $lineItem) {
            $lineItemEntity = new LineItemEntity();
            $lineItemEntity->setReservation($reservationEntity)
                ->setQuantity($lineItem->quantity()->toInteger())
                ->setLineItemId($lineItem->lineItemId()->toString())
                ->setPrice($this->fromDomainMapper->fromPrice($lineItem->price()))
                ->setProductId($lineItem->productId()->toString());

            $stepsToAdd = $this->mapIds($lineItemEntity->getStepIds(), $lineItem->mapStepIds())[1];

            $this->addStepsToEntity($lineItemEntity, $lineItem->filterSteps($stepsToAdd));

            $this->entityManager->persist($lineItemEntity);
        }
    }

    /**
     * @throws \Exception
     */
    private function updateLineItemsInEntity(ReservationEntity $reservationEntity, LineItems $lineItems): void
    {
        foreach ($lineItems as $lineItem) {
            $lineItemEntity = $reservationEntity->getLineItem($lineItem->lineItemId()->toString());

            list($stepsToRemove, $stepsToAdd, $stepsToUpdate) = $this->mapIds($lineItemEntity->getStepIds(), $lineItem->mapStepIds());

            $this->removeStepsFromEntity($lineItemEntity, $stepsToRemove);
            $this->addStepsToEntity($lineItemEntity, $lineItem->filterSteps($stepsToAdd));
            $this->updateStepsInEntity($lineItemEntity, $lineItem->filterSteps($stepsToUpdate));

            $lineItemEntity->setProductId($lineItem->productId()->toString())
                ->setQuantity($lineItem->quantity()->toInteger())
                ->setPrice($this->fromDomainMapper->fromPrice($lineItem->price()));
        }
    }

    /**
     * @param array<string> $stepsToRemove
     */
    private function removeStepsFromEntity(LineItemEntity $lineItemEntity, array $stepsToRemove): void
    {
        foreach ($stepsToRemove as $stepToRemoveId) {
            $stepEntityToRemove = $lineItemEntity->getStep($stepToRemoveId);

            if($stepEntityToRemove) {
                $this->entityManager->remove($stepEntityToRemove);
            }
        }
    }

    private function addStepsToEntity(LineItemEntity $lineItemEntity, Steps $stepsToAdd): void
    {
        foreach ($stepsToAdd as $stepToAdd) {
            $stepEntity = new StepEntity();
            $stepEntity->setLineItemEntity($lineItemEntity)
                ->setStepId($stepToAdd->stepId()->toString());

            $stepProductsToAdd = $this->mapIds($stepEntity->getStepProductIds(), $stepToAdd->mapStepProductIds())[1];

            $this->addStepProductsToEntity($stepEntity, $stepToAdd->filterStepProducts($stepProductsToAdd));

            $this->entityManager->persist($stepEntity);
        }
    }

    private function updateStepsInEntity(LineItemEntity $lineItemEntity, Steps $stepsToUpdate): void
    {
        foreach ($stepsToUpdate as $stepToUpdate) {
            $stepEntity = $lineItemEntity->getStep($stepToUpdate->stepId()->toString());

            if ($stepEntity) {
                list($stepProductsToRemove, $stepProductsToAdd, $stepProductsToUpdate) = $this->mapIds($stepEntity->getStepProductIds(), $stepToUpdate->mapStepProductIds());

                $this->removeStepProductsFromEntity($stepEntity, $stepProductsToRemove);
                $this->addStepProductsToEntity($stepEntity, $stepToUpdate->filterStepProducts($stepProductsToAdd));
                $this->updateStepProductsInEntity($stepEntity, $stepToUpdate->filterStepProducts($stepProductsToUpdate));
            }
        }
    }

    /**
     * @param array<string> $stepProductsToRemove
     */
    private function removeStepProductsFromEntity(StepEntity $stepEntity, array $stepProductsToRemove): void
    {
        foreach ($stepProductsToRemove as $stepProductToRemoveId) {
            $stepProductEntityToRemove = $stepEntity->getStepProduct($stepProductToRemoveId);

            if ($stepProductEntityToRemove) {
                $this->entityManager->remove($stepProductEntityToRemove);
            }
        }
    }

    private function addStepProductsToEntity(StepEntity $stepEntity, StepProducts $stepProductsToAdd): void
    {
        foreach ($stepProductsToAdd as $stepProduct) {
            $stepProductEntity = new StepProductEntity();

            $stepProductEntity->setPrice($this->fromDomainMapper->fromPrice($stepProduct->price()))
                ->setQuantity($stepProduct->quantity()->toInteger())
                ->setStepEntity($stepEntity)
                ->setProductId($stepProduct->productId()->toString());

            $this->entityManager->persist($stepProductEntity);
        }
    }

    private function updateStepProductsInEntity(StepEntity $stepEntity, StepProducts $stepProductsToUpdate): void
    {
        foreach ($stepProductsToUpdate as $stepProductToUpdate) {
            $stepProductEntity = $stepEntity->getStepProduct($stepProductToUpdate->productId()->toString());

            if ($stepProductEntity) {
                $stepProductEntity->setQuantity($stepProductToUpdate->quantity()->toInteger())
                    ->setPrice($this->fromDomainMapper->fromPrice($stepProductToUpdate->price()));
            }
        }
    }

    /**
     * @param array<string> $persistedIds
     * @param array<string> $domainIds
     *
     * @return array<int, array<string>>
     */
    private function mapIds(array $persistedIds, array $domainIds): array
    {
        $idsToRemove = array_diff($persistedIds, $domainIds);
        $idsToAdd = array_diff($domainIds, $persistedIds);
        $idsToUpdate = array_intersect($persistedIds, $domainIds);

        return [$idsToRemove, $idsToAdd, $idsToUpdate];
    }

    /**
     * @param ReservationEntity $reservationEntity
     * @param array<string> $inventoryItemsToRemove
     * @throws \Exception
     */
    private function removeInventoryItemsFromEntity(ReservationEntity $reservationEntity, array $inventoryItemsToRemove): void
    {
        foreach ($inventoryItemsToRemove as $inventoryItemToRemoveId) {
            $inventoryItemEntityToRemove = $reservationEntity->getInventoryItem($inventoryItemToRemoveId);

            $this->entityManager->remove($inventoryItemEntityToRemove);
        }
    }

    private function addInventoryItemsToEntity(ReservationEntity $reservationEntity, InventoryItems $inventoryItemsToAdd): void
    {
        foreach ($inventoryItemsToAdd as $inventoryItemToAdd) {
            $inventoryItemEntity = new InventoryItemEntity();

            $inventoryItemEntity->setInventoryItemId($inventoryItemToAdd->inventoryItemId()->toString())
                ->setProductId($inventoryItemToAdd->productId()->toString())
                ->setReservation($reservationEntity);

            $this->entityManager->persist($inventoryItemEntity);
        }
    }

    /**
     * @throws \Exception
     */
    private function updateInventoryItemsInEntity(ReservationEntity $reservationEntity, InventoryItems $inventoryItems): void
    {
        foreach ($inventoryItems as $inventoryItem) {
            $inventoryItemEntity = $reservationEntity->getInventoryItem($inventoryItem->inventoryItemId()->toString());

            $inventoryItemEntity->setProductId($inventoryItem->productId()->toString());
        }
    }
}
