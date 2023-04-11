<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Controller;

use Reservation\Application\Command\UpdateReservation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class UpdateReservationController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route(path: '/reservations/{reservation_id}', name: 'update_reservation', methods: ['POST'])]
    public function updateReservation(UpdateReservation $command): JsonResponse
    {
        $this->messageBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
