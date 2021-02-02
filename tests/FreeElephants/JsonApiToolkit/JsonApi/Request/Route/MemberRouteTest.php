<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request\Route;

use FreeElephants\JsonApiToolkit\JsonApi\Request\MappingConfigProvider;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;

class MemberRouteTest extends TestCase
{
    public function testConstructValid()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new MemberRoute($this->getMappingProvider(), 'v1', 'foo', $uuid, 'bar');
        $this->assertEquals('v1', $route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());
        $this->assertEquals($uuid, $route->getId());
        $this->assertEquals('bar', $route->getMemberName());

        $route = new MemberRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEmpty($route->getApiVersion());
        $this->assertEquals('foo', $route->getResourceName());
        $this->assertEquals($uuid, $route->getId());
        $this->assertEquals('bar', $route->getMemberName());

        $this->expectException(InvalidArgumentException::class);
        new MemberRoute($this->getMappingProvider(), '', 'foo', $uuid, 'baz');
    }

    public function testGetType()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new MemberRoute($this->getMappingProvider(), 'v1', 'foo', $uuid, 'bar');
        $this->assertEquals(RouteInterface::TYPE_MEMBER, $route->getType());
    }

    public function testGetEndpointTypeName()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new MemberRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEquals('BAR', $route->getEndpointTypeName());
    }

    public function testGetRouteParamName()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new MemberRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEquals('fooId', $route->getRouteParamName());
    }

    public function testGetRelationshipOriginName()
    {
        $uuid = Uuid::uuid4()->toString();
        $route = new MemberRoute($this->getMappingProvider(), '', 'foo', $uuid, 'bar');
        $this->assertEquals('bar', $route->getRelationshipOriginName());
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
            ]
        );
    }
}
