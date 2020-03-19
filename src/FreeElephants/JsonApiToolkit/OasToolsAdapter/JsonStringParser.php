<?php

namespace FreeElephants\JsonApiToolkit\OasToolsAdapter;

use cebe\openapi\Reader;
use cebe\openapi\spec\OpenApi;

class JsonStringParser implements OpenApiDocumentParserInterface
{

    public function parse(string $source): OpenApi
    {
        return Reader::readFromJson($source);
    }
}