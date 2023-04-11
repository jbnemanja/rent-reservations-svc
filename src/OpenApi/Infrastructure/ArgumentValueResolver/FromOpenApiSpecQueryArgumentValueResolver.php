<?php

declare(strict_types=1);

namespace OpenApi\Infrastructure\ArgumentValueResolver;

use function array_key_exists;
use function call_user_func;

use Generator;

use function is_array;
use function is_callable;

use OpenApi\Domain\Exception\FailedToCreateObjectFromOpenApiSpec;
use OpenApi\Domain\HasOpenApiSpec;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class FromOpenApiSpecQueryArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        $type = $argument->getType();

        if (!$type) {
            return false;
        }

        /** @var class-string[] $instanceOf */
        $instanceOf = class_implements($type);

        if (!is_array($instanceOf)) {
            return false;
        }

        return isset($instanceOf[HasOpenApiSpec::class]) && $request->isMethod(Request::METHOD_GET);
    }

    public function resolve(Request $request, ArgumentMetadata $argument): Generator
    {
        $payload = [];

        if (!empty($request->query->all())) {
            $payload = array_merge($request->query->all(), $payload);
        }

        if ($request->attributes->has('_route_params')) {
            foreach ($request->attributes->get('_route_params') as $key => $value) {
                if (array_key_exists($key, $payload)) {
                    throw new FailedToCreateObjectFromOpenApiSpec(sprintf('Duplicate key in query and route parameters: `%s`', $key));
                }
                $payload[$key] = $value;
            }
        }

        $callable = [$argument->getType(), 'fromArray'];
        if (is_callable($callable)) {
            yield call_user_func($callable, $payload);
        }
    }
}
