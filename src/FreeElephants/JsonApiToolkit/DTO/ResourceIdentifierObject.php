<?php

namespace FreeElephants\JsonApiToolkit\DTO;

/**
 * @deprecated
 * @see \FreeElephants\JsonApi\DTO\ResourceIdentifierObject
 */
class ResourceIdentifierObject
{
    public string $id;
    public string $type;

    public function __construct(array $data)
    {
        $this->id = $data['id'];
        $this->type = $data['type'];
    }
}
