<?php

namespace FreeElephants\JsonApiToolkit\Middleware\Validation;

use FreeElephants\Validation\ValidationResult;
use Neomerx\JsonApi\Schema\Error;
use Neomerx\JsonApi\Schema\ErrorCollection;

class ErrorCollectionFactory
{
    public function create(ValidationResult $validationResult): ErrorCollection
    {
        $errorCollection = new ErrorCollection();
        foreach ($validationResult->getErrors() as $key => $errors) {
            foreach ($errors as $ruleName => $message) {
                $error = new Error();
                $error->setStatus(400);
                $error->setTitle($message);
                $pointer = '/' . str_replace('.', '/', $key);
                $error->setSource([
                    'pointer' => $pointer,
                ]);
                $errorCollection->add($error);
            }
        }

        return $errorCollection;
    }
}
