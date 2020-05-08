<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Validation;

use FreeElephants\JsonApiToolkit\AbstractHttpTestCase;
use FreeElephants\JsonApiToolkit\Psr\ErrorFactory;
use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use FreeElephants\Validation\Rules;
use FreeElephants\Validation\ValidationResult;
use FreeElephants\Validation\ValidatorInterface;
use Neomerx\JsonApi\Encoder\Encoder;
use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationMiddlewareTest extends AbstractHttpTestCase
{
    public function testProcessBadRequestResponse()
    {
        $validator = $this->createMock(ValidatorInterface::class);
        $validationResult = new ValidationResult();
        $validationResult->addError('data.attributes.bar', 'required', 'Bar need baz');
        $validator->method('validate')->willReturn($validationResult);
        $jsonApiResponseFactory = new JsonApiResponseFactory(Encoder::instance(), new Psr17Factory(), new ErrorFactory());
        $middleware = new ValidationMiddleware($jsonApiResponseFactory, $validator, $this->createMock(Rules::class));
        $handlerMock = $this->createMock(RequestHandlerInterface::class);
        $request = $this->createServerRequest('POST', '/foo');
        $request = $request->withParsedBody([]);
        $response = $middleware->process($request, $handlerMock);

        $this->assertSame(400, $response->getStatusCode());
    }
}
