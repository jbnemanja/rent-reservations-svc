<?php

declare(strict_types=1);

namespace Reservation\Application\Factory;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use Reservation\Application\Command\CreateReservation;
use Reservation\Application\Command\UpdateReservation;
use Reservation\Application\Dto\AddressDto;
use Reservation\Application\Dto\ArrivalTimeDto;
use Reservation\Application\Dto\InventoryItemDto;
use Reservation\Application\Dto\InventoryItemsDto;
use Reservation\Application\Dto\LineItemDto;
use Reservation\Application\Dto\LineItemsDto;
use Reservation\Application\Dto\PriceDto;
use Reservation\Application\Dto\RentDurationDto;
use Reservation\Application\Dto\StepDto;
use Reservation\Application\Dto\StepProductDto;
use Reservation\Application\Dto\StepProductsDto;
use Reservation\Application\Dto\StepsDto;
use Reservation\Application\Dto\UserDto;

final class Factory
{
    /**
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public static function createReservation(array $data): CreateReservation
    {
        if (!(isset(
            $data['user'],
            $data['customer_id'],
            $data['line_items'],
            $data['inventory_items'],
            $data['currency'],
            $data['address'],
            $data['rent_duration'],
            $data['to_be_delivered_at'],
            $data['to_be_picked_up_at'],
            $data['to_be_delivered_by'],
            $data['to_be_picked_up_by'],
            $data['sale_channel_id'],
            $data['note']
        ))) {
            throw new Exception('Missing field');
        }

        if (
            !is_array($data['user'])
            || !is_array($data['address'])
            || !is_array($data['rent_duration'])
            || !is_array($data['to_be_delivered_at'])
            || !is_array($data['to_be_picked_up_at'])
            || !is_array($data['line_items'])
            || !is_array($data['inventory_items'])
        ) {
            throw new Exception('Invalid fields');
        }

        return new CreateReservation(
            (string) $data['customer_id'],
            self::lineItems($data['line_items']),
            self::inventoryItems($data['inventory_items']),
            (string) $data['currency'],
            new AddressDto(
                (string) $data['address']['city'],
                (string) $data['address']['street'],
                (string) $data['address']['street_number'],
                (string) $data['address']['apartment_number'],
                (string) $data['address']['floor_number'],
                (string) $data['address']['note'],
            ),
            new RentDurationDto(
                (string) $data['rent_duration']['time_unit'],
                (string) $data['rent_duration']['duration']
            ),
            new ArrivalTimeDto(
                self::toDateTimeImmutable((string) $data['to_be_delivered_at']['exactly_at']),
                self::toDateTimeImmutable((string) $data['to_be_delivered_at']['from']),
                self::toDateTimeImmutable((string) $data['to_be_delivered_at']['to'])
            ),
            new ArrivalTimeDto(
                // todo svuda proveriti da li je nesto null, ovde su uvek neka polja null npr
                self::toDateTimeImmutable((string) $data['to_be_picked_up_at']['exactly_at']),
                self::toDateTimeImmutable((string) $data['to_be_picked_up_at']['from']),
                self::toDateTimeImmutable((string) $data['to_be_picked_up_at']['to'])
            ),
            (string) $data['to_be_delivered_by'],
            (string) $data['to_be_picked_up_by'],
            (string) $data['sale_channel_id'],
            (string) $data['note'],
            new UserDto(
                (string) $data['user']['id'],
                (string) $data['user']['company_id']
            )
        );
    }

    /**
     * @throws Exception
     */
    private static function toDateTimeImmutable(?string $dateTime): ?DateTimeImmutable
    {
        return null !== $dateTime ? new DateTimeImmutable($dateTime, new DateTimeZone('UTC')) : null;
    }

    /**
     * @param array<string, mixed> $data
     * @throws Exception
     */
    public static function updateReservation(array $data): UpdateReservation
    {
        $user = new UserDto($data['user']['id'], $data['user']['company_id']);

        return new UpdateReservation(
            $data['reservation_id'],
            $data['customer_id'],
            self::lineItems($data['line_items']),
            self::inventoryItems($data['inventory_items']),
            $data['currency'],
            new AddressDto(
                $data['address']['city'],
                $data['address']['street'],
                $data['address']['street_number'],
                $data['address']['apartment_number'],
                $data['address']['floor_number'],
                $data['address']['note'],
            ),
            new RentDurationDto(
                $data['rent_duration']['time_unit'],
                $data['rent_duration']['duration']
            ),
            new ArrivalTimeDto(
                self::toDateTimeImmutable($data['to_be_delivered_at']['exactly_at']),
                self::toDateTimeImmutable($data['to_be_delivered_at']['from']),
                self::toDateTimeImmutable($data['to_be_delivered_at']['to'])
            ),
            new ArrivalTimeDto(
                self::toDateTimeImmutable($data['to_be_picked_up_at']['exactly_at']),
                self::toDateTimeImmutable($data['to_be_picked_up_at']['from']),
                self::toDateTimeImmutable($data['to_be_picked_up_at']['to'])
            ),
            $data['to_be_delivered_by'],
            $data['to_be_picked_up_by'],
            $data['reservation_status'],
            $data['sale_channel_id'],
            $data['note'],
            $user
        );
    }

    /**
     * @param array<string, array<string, mixed>> $lineItemsData
     * @return LineItemsDto
     */
    private static function lineItems(array $lineItemsData): LineItemsDto
    {
        $lineItems = [];
        foreach ($lineItemsData as $lineItem) {
            $steps = [];
            foreach ($lineItem['steps'] as $step) {
                $stepProducts = [];
                foreach ($step['step_products'] as $stepProduct) {
                    $stepProducts[] = new StepProductDto(
                        $stepProduct['product_id'],
                        new PriceDto(
                            $stepProduct['price']['amount'],
                            $stepProduct['price']['currency'],
                        ),
                        $stepProduct['quantity']
                    );
                }

                $steps[] = new StepDto($step['step_id'], new StepProductsDto(...$stepProducts));
            }
            $lineItems[] = new LineItemDto(
                $lineItem['line_item_id'],
                $lineItem['product_id'],
                new PriceDto(
                    $lineItem['price']['amount'],
                    $lineItem['price']['currency'],
                ),
                $lineItem['quantity'],
                new StepsDto(...$steps)
            );
        }

        return new LineItemsDto(...$lineItems);
    }

    /**
     * @param array<array<string, string>> $inventoryItemsData
     * @return InventoryItemsDto
     */
    private static function inventoryItems(array $inventoryItemsData): InventoryItemsDto
    {
        $inventoryItems = [];
        foreach ($inventoryItemsData as $inventoryItem) {
            $inventoryItems[] = new InventoryItemDto(
                $inventoryItem['product_id'],
                $inventoryItem['inventory_item_id']
            );
        }

        return new InventoryItemsDto(...$inventoryItems);
    }
}
