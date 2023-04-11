<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Controller;

use Reservation\Application\Command\CreateReservation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class CreateReservationController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route(path: '/reservations', name: 'create_reservation', methods: ['POST'])]
    public function createReservation(CreateReservation $command): JsonResponse
    {
        $this->messageBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_CREATED);
    }
}
