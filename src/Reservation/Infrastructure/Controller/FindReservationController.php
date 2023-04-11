<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Controller;

use Reservation\Application\Query\FindReservation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class FindReservationController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route(path: '/reservations/{reservation_id}', name: 'find_reservation', methods: ['GET'])]
    public function findReservation(FindReservation $query): JsonResponse
    {
        $reservation = $this->messageBus->dispatch($query);

        return new JsonResponse($reservation, Response::HTTP_OK);
    }
}
