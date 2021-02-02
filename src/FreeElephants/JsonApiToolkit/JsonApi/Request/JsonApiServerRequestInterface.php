<?php

namespace FreeElephants\JsonApiToolkit\JsonApi\Request;

use Psr\Http\Message\ServerRequestInterface;

interface JsonApiServerRequestInterface extends ServerRequestInterface
{
    const ATTRIBUTE_ROUTE_NAME = 'route';

    public function getDocumentId(): ?string;

    public function getDocumentType(): ?string;

    public function getPrimeAttributeName(): ?string;

    /**
     * @return mixed
     */
    public function getPrimeAttributeValue();

    public function getEndpointTypeName(): string;

    /**
     * @see MemberTypeEnum
     */
    public function getRelationshipOriginName(): string;

    public function getRequestType(): int;

    public function getDocumentAttributes(): ?object;

    /**
     * @return array<string, array>
     */
    public function getRelationships(): array;
}
