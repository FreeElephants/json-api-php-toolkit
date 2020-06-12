<?php

namespace FreeElephants\JsonApiToolkit\DTO;

use Psr\Http\Message\ServerRequestInterface;

class RelationshipToOne
{
    public ResourceIdentifierObject $data;

    public function __construct($data)
    {
        $this->data = new ResourceIdentifierObject($data['data']);
    }

    public static function fromRequest(ServerRequestInterface $request): self
    {
        $request->getBody()->rewind();
        $rawJson = $request->getBody()->getContents();
        $decodedJson = json_decode($rawJson, true);

        return new self($decodedJson);
    }
}
