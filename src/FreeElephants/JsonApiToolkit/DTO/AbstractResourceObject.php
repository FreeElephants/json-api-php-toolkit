<?php

namespace FreeElephants\JsonApiToolkit\DTO;

/**
 * @property AbstractAttributes    $attributes
 * @property AbstractRelationships $relationships
 */
class AbstractResourceObject
{
    public string $id;
    public string $type;

    public function __construct(array $data)
    {
        $this->id = $data['id'];

        $concreteClass = new \ReflectionClass($this);

        if (property_exists($this, 'attributes')) {
            $attributesProperty = $concreteClass->getProperty('attributes');
            $attributesClass = $attributesProperty->getType()->getName();
            $this->attributes = new $attributesClass($data['attributes']);
        }

        if (property_exists($this, 'relationships')) {
            $concreteClass = new \ReflectionClass($this);
            $relationshipsProperty = $concreteClass->getProperty('relationships');
            $relationshipsClass = $relationshipsProperty->getType()->getName();
            $this->relationships = new $relationshipsClass($data['relationships']);
        }
    }
}
