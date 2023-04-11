<?php

declare(strict_types=1);

namespace OpenApi\Infrastructure\EventSubscriber;

use OpenApi\Domain\Exception\FailedToCreateObjectFromOpenApiSpec;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

final class FailedToCreateObjectFromOpenApiSpecSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => [
                ['onFailedToCreateObjectFromOpenApiSpec', 0],
            ],
        ];
    }

    public function onFailedToCreateObjectFromOpenApiSpec(ExceptionEvent $event): void
    {
        $throwable = $event->getThrowable();

        if (!$throwable instanceof FailedToCreateObjectFromOpenApiSpec) {
            return;
        }

        $event->setResponse(
            new JsonResponse([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $throwable->getMessage(),
            ], Response::HTTP_BAD_REQUEST)
        );
    }
}
