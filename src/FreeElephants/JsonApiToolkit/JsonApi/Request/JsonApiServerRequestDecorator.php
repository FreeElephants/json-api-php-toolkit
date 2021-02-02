<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request;

use FreeElephants\JsonApiToolkit\JsonApi\Request\Route\RelationshipRoute;
use FreeElephants\JsonApiToolkit\JsonApi\Request\Route\RouteFactory;
use FreeElephants\JsonApiToolkit\JsonApi\Request\Route\RouteInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UriInterface;
use stdClass;

class JsonApiServerRequestDecorator implements JsonApiServerRequestInterface
{
    private ServerRequestInterface $request;
    private ?array $decodedBody;

    public function __construct(ServerRequestInterface $request, RouteFactory $routeFactory)
    {
        $this->request = $request;
    }

    public function getProtocolVersion()
    {
        return $this->request->getProtocolVersion();
    }

    public function withProtocolVersion($version)
    {
        return $this->request->withProtocolVersion($version);
    }

    public function getHeaders()
    {
        return $this->request->getHeaders();
    }

    public function hasHeader($name)
    {
        return $this->request->hasHeader($name);
    }

    public function getHeader($name)
    {
        return $this->request->getHeader($name);
    }

    public function getHeaderLine($name)
    {
        return $this->request->getHeaderLine($name);
    }

    public function withHeader($name, $value)
    {
        return $this->request->withHeader($name, $value);
    }

    public function withAddedHeader($name, $value)
    {
        return $this->request->withAddedHeader($name, $value);
    }

    public function withoutHeader($name)
    {
        return $this->request->withoutHeader($name);
    }

    public function getBody()
    {
        return $this->request->getBody();
    }

    public function withBody(StreamInterface $body)
    {
        return $this->request->withBody($body);
    }

    public function getRequestTarget()
    {
        return $this->request->getRequestTarget();
    }

    public function withRequestTarget($requestTarget)
    {
        return $this->request->withRequestTarget($requestTarget);
    }

    public function getMethod()
    {
        return $this->request->getMethod();
    }

    public function withMethod($method)
    {
        return $this->request->withMethod($method);
    }

    public function getUri()
    {
        return $this->request->getUri();
    }

    public function withUri(UriInterface $uri, $preserveHost = false)
    {
        return $this->request->withUri($uri, $preserveHost);
    }

    public function getServerParams()
    {
        return $this->request->getServerParams();
    }

    public function getCookieParams()
    {
        return $this->request->getCookieParams();
    }

    public function withCookieParams(array $cookies)
    {
        return $this->request->withCookieParams($cookies);
    }

    public function getQueryParams()
    {
        return $this->request->getQueryParams();
    }

    public function withQueryParams(array $query)
    {
        return $this->request->withQueryParams($query);
    }

    public function getUploadedFiles()
    {
        return $this->request->getUploadedFiles();
    }

    public function withUploadedFiles(array $uploadedFiles)
    {
        return $this->request->withUploadedFiles($uploadedFiles);
    }

    public function getParsedBody()
    {
        return $this->request->getParsedBody();
    }

    public function withParsedBody($data)
    {
        return $this->request->withParsedBody($data);
    }

    public function getAttributes()
    {
        return $this->request->getAttributes();
    }

    public function getAttribute($name, $default = null)
    {
        return $this->request->getAttribute($name, $default);
    }

    public function withAttribute($name, $value)
    {
        return $this->request->withAttribute($name, $value);
    }

    public function withoutAttribute($name)
    {
        return $this->request->withoutAttribute($name);
    }

    private function getRoute(): RouteInterface
    {
        return $this->request->getAttribute(JsonApiServerRequestInterface::ATTRIBUTE_ROUTE_NAME);
    }

    private function getDecodedBody(): ?array
    {
        if (isset($this->decodedBody)) {
            return $this->decodedBody;
        }

        $this->request->getBody()->rewind();
        $this->decodedBody = json_decode($this->request->getBody()->getContents(), true);

        return $this->decodedBody;
    }

    public function getDocumentId(): ?string
    {
        return $this->getDecodedBody()['data']['id'] ?? null;
    }

    public function getDocumentType(): ?string
    {
        return $this->getDecodedBody()['data']['type'] ?? null;
    }

    public function getPrimeAttributeName(): ?string
    {
        return $this->getRoute()->getRouteParamName();
    }

    /**
     * @return mixed
     */
    public function getPrimeAttributeValue()
    {
        return $this->getAttribute($this->getPrimeAttributeName());
    }

    public function getEndpointTypeName(): string
    {
        return $this->getRoute()->getEndpointTypeName();
    }

    /**
     * @see MemberTypeEnum
     */
    public function getRelationshipOriginName(): string
    {
        return $this->getRoute()->getRelationshipOriginName();
    }

    public function getRequestType(): int
    {
        return $this->getRoute()->getType();
    }

    public function getDocumentAttributes(): ?stdClass
    {
        $this->request->getBody()->rewind();

        $decodedBody = json_decode($this->request->getBody()->getContents());

        return $decodedBody->data->attributes ?? null;
    }

    /**
     * @return array<string, array>
     */
    public function getRelationships(): array
    {
        $body = $this->getDecodedBody();

        switch ($this->getRoute()->getType()) {
            case RouteInterface::TYPE_RESOURCES_COLLECTION:
            case RouteInterface::TYPE_RESOURCE:
            case RouteInterface::TYPE_MEMBER:
                $relationships = $body['data']['relationships'] ?? [];
                break;
            case RouteInterface::TYPE_RELATIONSHIP:
                /** @var RelationshipRoute $route */
                $route = $this->getRoute();
                $relationships = [$route->getRelationshipName() => $body];
                break;
            default:
                $relationships = [];
        }

        return $relationships;
    }
}
