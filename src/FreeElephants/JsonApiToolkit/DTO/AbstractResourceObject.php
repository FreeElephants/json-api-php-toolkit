<?php

namespace FreeElephants\JsonApiToolkit\DTO;

/**
 * @property AbstractAttributes $attributes
 */
abstract class AbstractResourceObject
{
    public string $id;
    public string $type;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $concreteClass = new \ReflectionClass($this);
        $attributesProperty = $concreteClass->getProperty('attributes');
        $attributesClass = $attributesProperty->getType()->getName();
        $this->attributes = new $attributesClass($data['attributes']);
    }
}
