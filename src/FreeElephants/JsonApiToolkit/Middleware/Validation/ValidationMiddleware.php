<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Validation;

use FreeElephants\JsonApiToolkit\Psr\JsonApiResponseFactory;
use FreeElephants\Validation\Rules;
use FreeElephants\Validation\ValidatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationMiddleware implements MiddlewareInterface
{
    private ValidatorInterface $validator;
    private JsonApiResponseFactory $jsonApiResponseFactory;
    private Rules $rules;

    public function __construct(JsonApiResponseFactory $jsonApiResponseFactory, ValidatorInterface $validator, Rules $rules)
    {
        $this->jsonApiResponseFactory = $jsonApiResponseFactory;
        $this->validator = $validator;
        $this->rules = $rules;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $language = \Locale::getPrimaryLanguage(\Locale::acceptFromHttp($request->getHeaderLine('Accept-Language')));
        $result = $this->validator->validate($request->getParsedBody(), $this->rules, $language);
        if ($result->valid()) {
            return $handler->handle($request);
        }
        $errorCollection = (new ErrorCollectionFactory())->create($result);

        return $this->jsonApiResponseFactory->createErrorResponse($errorCollection, 400);
    }
}
