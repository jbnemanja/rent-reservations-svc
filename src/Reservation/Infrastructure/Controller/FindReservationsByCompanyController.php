<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Controller;

use Reservation\Application\Query\FindReservations;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class FindReservationsByCompanyController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route(path: '/reservations', name: 'find_reservations', methods: ['GET'])]
    public function findReservations(FindReservations $query): JsonResponse
    {
        $reservations = $this->messageBus->dispatch($query);

        return new JsonResponse($reservations, Response::HTTP_OK);
    }
}
