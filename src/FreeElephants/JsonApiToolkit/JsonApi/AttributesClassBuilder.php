<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use cebe\openapi\spec\OpenApi;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;

class AttributesClassBuilder
{

    private AttributesSchemaBuilder $attributesSchemaFinder;
    /**
     * @var DTOClassesNamingStrategyInterface
     */
    private DTOClassesNamingStrategyInterface $namingStrategy;

    public function __construct(AttributesSchemaBuilder $attributesSchemaFinder = null, DTOClassesNamingStrategyInterface $namingStrategy = null)
    {
        $this->attributesSchemaFinder = $attributesSchemaFinder ?: new AttributesSchemaBuilder();
        $this->namingStrategy = $namingStrategy ?: new DefaultDTOClassesNamingStrategy();
    }

    public function buildClass(OpenApi $openapi, string $resourceType, string $namespace = null): ClassType
    {
        $phpNamespace = $namespace ? new PhpNamespace($namespace) : null;
        $className = $this->namingStrategy->buildAttributesClassName($resourceType);
        $attributesClass = new ClassType($className, $phpNamespace);
        $attributesClass->setFinal(true);

        $schema = $this->attributesSchemaFinder->build($openapi, $resourceType);
        foreach ($schema->getAttributes() as $attribute) {
            $attributesClass->addProperty($attribute->getName())->setType($attribute->getType());
        }

        return $attributesClass;
    }
}