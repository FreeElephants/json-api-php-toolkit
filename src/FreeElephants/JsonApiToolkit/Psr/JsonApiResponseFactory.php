<?php

namespace FreeElephants\JsonApiToolkit\Psr;

use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;
use Neomerx\JsonApi\Schema\ErrorCollection;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

class JsonApiResponseFactory
{
    private ResponseFactoryInterface $responseFactory;
    private EncoderInterface $encoder;
    private ErrorFactory $errorFactory;

    public function __construct(EncoderInterface $encoder, ResponseFactoryInterface $responseFactory, ErrorFactory $errorFactory)
    {
        $this->encoder = $encoder;
        $this->responseFactory = $responseFactory;
        $this->errorFactory = $errorFactory;
    }

    public function createResponse($data, ServerRequestInterface $request = null): ResponseInterface
    {
        $includedPath = [];
        if ($request) {
            $queryParams = $request->getQueryParams();
            if (array_key_exists('include', $queryParams)) {
                $includedPath = explode(',', $queryParams['include']);
            }
        }

        $fieldSets = [];
        if ($request) {
            $queryParams = $request->getQueryParams();
            if (array_key_exists('fields', $queryParams)) {
                foreach ($queryParams['fields'] as $type => $typeFieldsString) {
                    $fieldSets[$type] = explode(',', $typeFieldsString);
                }
            }
        }
        $content = $this->encoder->withIncludedPaths($includedPath)->withFieldSets($fieldSets)->encodeData($data);

        $response = $this->createPsrResponse();
        $response->getBody()->write($content);
        $response->getBody()->rewind();

        return $response;
    }

    public function createErrorResponse(ErrorCollection $errors, int $status): ResponseInterface
    {
        $response = $this->createPsrResponse($status);
        $content = $this->encoder->encodeErrors($errors);
        $response->getBody()->write($content);

        return $response;
    }

    public function createErrorResponseFromException(Throwable $e, int $status, ServerRequestInterface $request, ?array $source = null): ResponseInterface
    {
        $errorCollection = new ErrorCollection();
        $errorCollection->add($this->errorFactory->fromThrowable($e, $status, $request, $source));

        return $this->createErrorResponse($errorCollection, $status);
    }

    public function createSingleErrorResponse(string $title, int $status, ServerRequestInterface $request, ?array $source = null): ResponseInterface
    {
        $errorCollection = new ErrorCollection();
        $errorCollection->add($this->errorFactory->createError($title, $status, $request, $source));

        return $this->createErrorResponse($errorCollection, $status);
    }

    private function createPsrResponse(int $status = 200): ResponseInterface
    {
        return $this->responseFactory->createResponse($status)
            ->withHeader('Content-Type', 'application/vnd.api+json')
            ->withHeader('Access-Control-Allow-Origin', '*');
    }
}
