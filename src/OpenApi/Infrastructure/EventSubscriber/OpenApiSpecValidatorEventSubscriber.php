<?php

declare(strict_types=1);

namespace OpenApi\Infrastructure\EventSubscriber;

use function is_string;

use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidBody;
use League\OpenAPIValidation\PSR7\Exception\Validation\InvalidPath;
use League\OpenAPIValidation\PSR7\Exception\ValidationFailed;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\Exception\SchemaMismatch;
use Psr\Http\Message\ServerRequestFactoryInterface;
use RuntimeException;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Throwable;

final class OpenApiSpecValidatorEventSubscriber implements EventSubscriberInterface
{
    private PsrHttpFactory $httpMessageFactory;
    private string $openApiSpecFilePath;

    public function __construct(string $openApiSpecFilePath, ServerRequestFactoryInterface $psr17Factory)
    {
        $this->openApiSpecFilePath = $openApiSpecFilePath;
        $this->httpMessageFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);
    }

    /**
     * @return string[]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onRequest',
        ];
    }

    public function onRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $specFile = file_get_contents($this->openApiSpecFilePath);
        if (!is_string($specFile)) {
            throw new RuntimeException('Expected OpenAPI spec, but got none.');
        }

        $validator = (new ValidatorBuilder())->fromYaml($specFile)->getServerRequestValidator();

        $request = $event->getRequest();
        $psr7Request = $this->httpMessageFactory->createRequest($request);

        try {
            $validator->validate($psr7Request);
        } catch (InvalidPath $throwable) {
            $address = $throwable->getAddress();
            $event->setResponse(
                new JsonResponse(
                    [
                        'error' => $throwable->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST
                )
            );
        } catch (InvalidBody $throwable) {
            $event->setResponse(
                new JsonResponse(
                    [
                        'error' => $throwable->getPrevious()->getMessage(),
                    ],
                    Response::HTTP_BAD_REQUEST
                )
            );
        } catch (ValidationFailed $throwable) {
            $event->setResponse(
                new JsonResponse(
                    [
                        'error' => $throwable->getMessage(),
                        'violations' => $this->resolveViolations($throwable->getPrevious()),
                    ],
                    Response::HTTP_UNPROCESSABLE_ENTITY
                )
            );
        }
    }

    /**
     * @return array<int|string, mixed>
     */
    private function resolveViolations(Throwable $throwable = null): array
    {
        $violations = [];

        if ($throwable instanceof SchemaMismatch) {
            if ($dataBreadCrumb = $throwable->dataBreadCrumb()) {
                $chain = $dataBreadCrumb->buildChain();
                $violations[$chain[0]] = $throwable->getMessage();
            }
        }

        return $violations;
    }
}
