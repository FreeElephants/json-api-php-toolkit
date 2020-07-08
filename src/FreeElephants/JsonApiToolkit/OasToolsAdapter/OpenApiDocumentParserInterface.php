<?php

namespace FreeElephants\JsonApiToolkit\OasToolsAdapter;

use cebe\openapi\spec\OpenApi;

interface OpenApiDocumentParserInterface
{

    public function parse(string $source): OpenApi;
}
