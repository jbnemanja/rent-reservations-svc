<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Controller;

use Reservation\Application\Command\DeleteReservation;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

final class DeleteReservationController
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    #[Route(path: '/reservations/{reservation_id}', name: 'delete_reservation', methods: ['DELETE'])]
    public function deleteReservation(DeleteReservation $command): JsonResponse
    {
        $this->messageBus->dispatch($command);

        return new JsonResponse(null, Response::HTTP_OK);
    }
}
