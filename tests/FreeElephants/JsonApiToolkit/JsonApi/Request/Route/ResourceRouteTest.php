<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class ResourceRouteTest extends TestCase
{
    public function testConstructValid()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new ResourceRoute($this->getMappingProvider(), 'v1', 'foo', $uuid);
        $this->assertEquals('v1', $route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());
        $this->assertEquals($uuid, $route->getId());

        $route = new ResourceRoute($this->getMappingProvider(), '', 'foo', $uuid);
        $this->assertEmpty($route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());
        $this->assertEquals($uuid, $route->getId());
    }

    public function testGetType()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new ResourceRoute($this->getMappingProvider(), '', 'foo', $uuid);
        $this->assertEquals(RouteInterface::TYPE_RESOURCE, $route->getType());
    }

    public function testGetRouteParamName()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new ResourceRoute($this->getMappingProvider(), '', 'foo', $uuid);
        $this->assertEquals('fooId', $route->getRouteParamName());
    }

    private function getMappingProvider()
    {
        return new MappingConfigProvider(
            [
                MappingConfigProvider::ENDPOINT_TYPE_TO_DOCUMENT_TYPE => [
                    'foo' => 'FOO',
                ],
                MappingConfigProvider::RESOURCE_TYPE_TO_ROUTE_PARAM_KEY => [
                    'foo' => 'fooId',
                ],
            ]
        );
    }
}
