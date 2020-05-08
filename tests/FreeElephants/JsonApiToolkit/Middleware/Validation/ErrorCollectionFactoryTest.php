<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Validation;

use FreeElephants\JsonApiToolkit\AbstractTestCase;
use FreeElephants\Validation\ValidationResult;
use Neomerx\JsonApi\Encoder\Encoder;

class ErrorCollectionFactoryTest extends AbstractTestCase
{
    public function testCreateResponse()
    {
        $validationErrorResponseFactory = new ErrorCollectionFactory();
        $errorBag = new ValidationResult();
        $errorBag->addError('data.attributes.email', 'required', 'Email is required');
        $errorBag->addError('data.attributes.password', 'min:7', 'Password must be more than 7 chars');
        $collection = $validationErrorResponseFactory->create($errorBag);
        $responseBodyWithErrors = Encoder::instance()->encodeErrors($collection);
        $expectedJson = <<<JSON
{
    "errors": [
        {
            "status": "400",
            "title": "Email is required",
            "source": {
                "pointer": "/data/attributes/email"
            }
        },
        {
            "status": "400",
            "title": "Password must be more than 7 chars",
            "source": {
                "pointer": "/data/attributes/password"
            }
        }
    ]
}
JSON;

        $this->assertJsonStringEqualsJsonString($expectedJson, $responseBodyWithErrors);
    }
}
