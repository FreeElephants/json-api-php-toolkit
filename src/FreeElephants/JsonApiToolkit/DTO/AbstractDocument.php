<?php

namespace FreeElephants\JsonApiToolkit\DTO;

use Psr\Http\Message\ServerRequestInterface;

/**
 * @property AbstractResourceObject $data
 */
abstract class AbstractDocument
{
    public function __construct(array $data)
    {
        $concreteClass = new \ReflectionClass($this);
        $dataProperty = $concreteClass->getProperty('data');
        $dataClassName = $dataProperty->getType()->getName();
        $this->data = new $dataClassName($data['data']);
    }

    public static function fromRequest(ServerRequestInterface $request): self
    {
        $request->getBody()->rewind();
        $rawJson = $request->getBody()->getContents();
        $decodedJson = json_decode($rawJson, true);

        return new static($decodedJson);
    }
}
