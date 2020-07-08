<?php

namespace FreeElephants\JsonApiToolkit\JsonApi;

use cebe\openapi\spec\OpenApi;
use cebe\openapi\spec\Schema;

class AttributesSchemaBuilder
{

    public function build(OpenApi $openapi, string $jsonApiResourceTypeName): AttributesSchema
    {
        return $this->getAttributesSchema($openapi->components->schemas, $jsonApiResourceTypeName);
    }

    private function getAttributesSchema(array $schemas, string $jsonApiResourceTypeName): AttributesSchema
    {
        foreach ($schemas as $schema) {
            /**@var Schema $schema */
            if ($this->isAttributesMappingSchema($schema, $jsonApiResourceTypeName)) {
                return $this->buildAttributesSchema($schema, $jsonApiResourceTypeName);
            }
            if ($schema->allOf) {
                foreach ($schema->allOf as $allOfItemSchema) {
                    foreach ($allOfItemSchema->properties as $propertyName => $propertySchema) {
                        if ($propertyName === 'attributes') {
                            return $this->buildAttributesSchema($propertySchema, $jsonApiResourceTypeName);
                        }
                    }
                }
            }
        }

        $message = 'Schema for %s attributes structure not found in given OAS';
        throw new \DomainException(sprintf($message, $jsonApiResourceTypeName));
    }

    private function buildAttributesSchema(Schema $schema, string $jsonApiResourceTypeName): AttributesSchema
    {
        $attributesSchema = new AttributesSchema();
        if ($schema->allOf) {
            foreach ($schema->allOf as $allOfItemSchema) {
                $this->mergeJsonSchemaPropertiesToAttributesSchema($allOfItemSchema, $attributesSchema);
            }
        }
        $this->mergeJsonSchemaPropertiesToAttributesSchema($schema, $attributesSchema);

        return $attributesSchema;
    }

    private function isAttributesMappingSchema(Schema $schema, string $jsonApiResourceTypeName): bool
    {
        return isset($schema->{'x-json-api'}) && isset($schema->{'x-json-api'}['type']) && isset($schema->{'x-json-api'}['value']) && $schema->{'x-json-api'}['type'] === $jsonApiResourceTypeName && $schema->{'x-json-api'}['value'] === 'attributes';
    }

    private function mergeJsonSchemaPropertiesToAttributesSchema(Schema $schema, AttributesSchema $attributesSchema)
    {
        foreach ($schema->properties as $propertyName => $propertyParams) {
            $attributesSchema->addAttribute(new Attribute($propertyName, $propertyParams->type));
        }
    }

}
