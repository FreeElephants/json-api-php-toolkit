<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use cebe\openapi\spec\OpenApi;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class DataTransferObjectClassSourceCodeGenerator
{
    private AttributesClassBuilder $attributesClassBuilder;
    private DTOClassesNamingStrategyInterface $namingStrategy;

    public function __construct(AttributesClassBuilder $attributesClassBuilder = null, DTOClassesNamingStrategyInterface $namingStrategy = null)
    {
        $this->attributesClassBuilder = $attributesClassBuilder ?: new AttributesClassBuilder();
        $this->namingStrategy = $namingStrategy ?: new DefaultDTOClassesNamingStrategy();
    }

    public function generate(OpenApi $openApi, string $jsonApiResourceType, string $namespace = null): DataTransferObjectsSet
    {
        $phpNamespace = $namespace ? new PhpNamespace($namespace) : null;
        $topLevelDocumentClass = new ClassType($this->namingStrategy->buildDocumentClassName($jsonApiResourceType), $phpNamespace);
        $resourceObjectClass = new ClassType($this->namingStrategy->buildResourceObjectClassName($jsonApiResourceType));
        $attributesClass = $this->attributesClassBuilder->buildClass($openApi, $jsonApiResourceType, $namespace);

        $topLevelDocumentClass->setFinal(true)
            ->addProperty('data')->setType($resourceObjectClass->getName());

        $resourceObjectClass->addProperty('id')->setType('string');
        $resourceObjectClass->addProperty('type')->setType('string');
        $resourceObjectClass->setFinal(true)
            ->addProperty('attributes')->setType($attributesClass->getName());

        return new DataTransferObjectsSet($topLevelDocumentClass, $resourceObjectClass, $attributesClass);
    }
}