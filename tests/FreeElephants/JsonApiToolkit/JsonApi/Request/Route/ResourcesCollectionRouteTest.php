<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class ResourcesCollectionRouteTest extends TestCase
{
    public function testConstruct()
    {
        $route = new ResourcesCollectionRoute($this->getMappingProvider(), 'v1', 'foo');
        $this->assertEquals('v1', $route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());

        $route = new ResourcesCollectionRoute($this->getMappingProvider(), '', 'foo');
        $this->assertEmpty($route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());

        $this->expectException(InvalidArgumentException::class);
        new ResourcesCollectionRoute($this->getMappingProvider(), '', 'baz');
    }

    public function testGetType()
    {
        $route = new ResourcesCollectionRoute($this->getMappingProvider(), '', 'foo');
        $this->assertEquals(RouteInterface::TYPE_RESOURCES_COLLECTION, $route->getType());
    }

    public function testGetEndpointTypeName()
    {
        $route = new ResourcesCollectionRoute($this->getMappingProvider(), '', 'foo');
        $this->assertEquals('foo', $route->getEndpointTypeName());
    }

    public function testGetRouteParamName()
    {
        $route = new ResourcesCollectionRoute($this->getMappingProvider(), '', 'foo');
        $this->assertNull($route->getRouteParamName());
    }

    public function testGetRelationshipOriginName()
    {
        $route = new ResourcesCollectionRoute($this->getMappingProvider(), '', 'foo');
        $this->assertEquals('foo', $route->getRelationshipOriginName());
    }

    private function getMappingProvider()
    {
        return new MappingConfigProvider(
            [
                MappingConfigProvider::ENDPOINT_TYPE_TO_DOCUMENT_TYPE => [
                    'foo' => 'FOO',
                ],
            ]
        );
    }
}
