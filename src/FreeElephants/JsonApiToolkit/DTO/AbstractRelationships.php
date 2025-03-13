<?php

namespace FreeElephants\JsonApiToolkit\DTO;

/**
 * @deprecated
 * @see \FreeElephants\JsonApi\DTO\AbstractRelationships
 */
abstract class AbstractRelationships
{
    public function __construct(array $data)
    {
        foreach ($data as $relationshipName => $relationshipsData) {
            $this->{$relationshipName} = new RelationshipToOne($relationshipsData);
        }
    }
}
