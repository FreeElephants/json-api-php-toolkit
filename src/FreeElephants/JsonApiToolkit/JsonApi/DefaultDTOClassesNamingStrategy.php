<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

class DefaultDTOClassesNamingStrategy implements DTOClassesNamingStrategyInterface
{

    public function buildAttributesClassName(string $jsonApiResourceType): string
    {
        return ucfirst($jsonApiResourceType) . 'Attributes';
    }

    public function buildDocumentClassName(string $jsonApiResourceType): string
    {
        return ucfirst($jsonApiResourceType) . 'Document';
    }

    public function buildResourceObjectClassName(string $jsonApiResourceType): string
    {
        return ucfirst($jsonApiResourceType) . 'ResourceObject';
    }
}
