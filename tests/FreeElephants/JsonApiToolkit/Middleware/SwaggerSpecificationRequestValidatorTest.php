<?php

namespace FreeElephants\JsonApiToolkit\Middleware;

use FreeElephants\JsonApiToolkit\AbstractHttpTestCase;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;

class SwaggerSpecificationRequestValidatorTest extends AbstractHttpTestCase
{

    private const SWAGGER_FILE = self::FIXTERE_PATH . '/swagger-example-for-request-validation.yml';

    public function testProcessSuccess()
    {
        $serverRequest = $this->createServerRequest('POST', '/v1/articles')
            ->withHeader('Content-Type', 'application/vnd.api+json')
            ->withHeader('Authorization', 'Bearer some-hash');
        $serverRequest->getBody()->write(<<<JSON
{
    "data": {
        "id": "ffe043f6-c114-11ea-88c9-03dcff0521ca",
        "type": "articles",
        "attributes": {
            "title": "foo",
            "text": "lorem ipsum"
        }
    }
}
JSON
        );
        $validator = $this->buildValidatorMiddleware(self::SWAGGER_FILE);

        $response = $validator->process($serverRequest, $this->createRequestHandlerWithAssertions(function () {
            return $this->createResponse(201);
        }));

        $this->assertResponseHasStatus($response, 201);
    }

    public function testProcessWithInvalidJson()
    {
        $serverRequest = $this->createServerRequest('POST', '/v1/articles')
            ->withHeader('Content-Type', 'application/vnd.api+json')
            ->withHeader('Authorization', 'Bearer some-hash');
        $serverRequest->getBody()->write(<<<JSON
INVALID_JSON
JSON
        );
        $validator = $this->buildValidatorMiddleware(self::SWAGGER_FILE);

        $response = $validator->process($serverRequest, $this->createRequestHandlerWithAssertions(function () {
        }));

        $this->assertResponseHasStatus($response, 400);
    }

    public function testProcessWithMissingRequiredField()
    {

        $serverRequest = $this->createServerRequest('POST', '/v1/articles')
            ->withHeader('Content-Type', 'application/vnd.api+json')
            ->withHeader('Authorization', 'Bearer some-hash');
        $serverRequest->getBody()->write(<<<JSON
{
    "data": {
    }
}
JSON
        );
        $validator = $this->buildValidatorMiddleware(self::SWAGGER_FILE);

        $response = $validator->process($serverRequest, $this->createRequestHandlerWithAssertions(function () {}));

        $this->assertResponseHasStatus($response, 400);
    }

    public function testProcessMissingSecurity()
    {
        $serverRequest = $this->createServerRequest('POST', '/v1/articles')
            ->withHeader('Content-Type', 'application/vnd.api+json');
        $serverRequest->getBody()->write(<<<JSON
{
    "title": "foo",
    "text": "lorem ipsum"
}
JSON
        );
        $validator = $this->buildValidatorMiddleware(self::SWAGGER_FILE);

        $response = $validator->process($serverRequest, $this->createRequestHandlerWithAssertions(function () {}));

        $this->assertResponseHasStatus($response, 401);
    }

    private function buildValidatorMiddleware(string $swaggerFilename): SwaggerSpecificationRequestValidator
    {
        $serverRequestValidator = (new ValidatorBuilder())
            ->fromYamlFile($swaggerFilename)
            ->getServerRequestValidator();

        return new SwaggerSpecificationRequestValidator($serverRequestValidator, $this->createJsonApiResponseFactory());
    }
}
