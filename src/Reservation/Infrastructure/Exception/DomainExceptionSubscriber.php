<?php

declare(strict_types=1);

namespace Reservation\Infrastructure\Exception;

use Reservation\Domain\Exception\DomainExceptionInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Messenger\Exception\HandlerFailedException;

final class DomainExceptionSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['processException', 0],
            ],
        ];
    }

    public function processException(ExceptionEvent $exceptionEvent): void
    {
        $throwable = $exceptionEvent->getThrowable();

        if ($throwable instanceof HandlerFailedException) {
            $throwable = $throwable->getPrevious();
        }

        if (!$throwable instanceof DomainExceptionInterface) {
            return;
        }

        $exceptionEvent->setResponse(new JsonResponse([
            'message' => $throwable->getMessage(),
        ], (int) $throwable->httpResponseCode()));
    }
}
