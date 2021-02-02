<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class RelationshipRouteTest extends TestCase
{
    public function testConstructValid()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new RelationshipRoute($this->getMappingProvider(), 'v1', 'foo', $uuid, 'bar');
        $this->assertEquals('v1', $route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());
        $this->assertEquals($uuid, $route->getId());
        $this->assertEquals('bar', $route->getRelationshipName());

        $route = new RelationshipRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEmpty($route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());
        $this->assertEquals($uuid, $route->getId());
        $this->assertEquals('bar', $route->getRelationshipName());

        $this->expectException(InvalidArgumentException::class);
        new RelationshipRoute($this->getMappingProvider(), '', 'foo', $uuid, 'baz');
    }

    public function testGetType()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new RelationshipRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEquals(RouteInterface::TYPE_RELATIONSHIP, $route->getType());
    }

    public function testGetEndpointTypeName()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new RelationshipRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEquals('BAR', $route->getEndpointTypeName());
    }

    public function testGetRouteParamName()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new RelationshipRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEquals('fooId', $route->getRouteParamName());
    }

    public function testGetRelationshipOriginName()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new RelationshipRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEquals('foo', $route->getRelationshipOriginName());
    }

    private function getMappingProvider()
    {
        return new MappingConfigProvider(
            [
                MappingConfigProvider::ENDPOINT_TYPE_TO_DOCUMENT_TYPE => [
                    'foo' => 'FOO',
                    'bar' => 'BAR',
                ],
                MappingConfigProvider::RESOURCE_TYPE_TO_ROUTE_PARAM_KEY => [
                    'foo' => 'fooId',
                ],
            ],
        );
    }
}
