<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

interface DTOClassesNamingStrategyInterface
{

    public function buildAttributesClassName(string $jsonApiResourceType): string;

    public function buildDocumentClassName(string $jsonApiResourceType): string;

    public function buildResourceObjectClassName(string $jsonApiResourceType): string;
}