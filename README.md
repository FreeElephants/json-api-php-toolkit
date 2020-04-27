# Json Api Toolkit

## Features: 
* [x] Build FastRoute Dispatcher by defined in OAS3 `operationsIds` values
* [x] Serialize Doctrine entities with Neomerx schemas (resolve issue https://github.com/neomerx/json-api/issues/40)  
* [ ] Generate PHP Data Transfer Objects by OAS3 `reponseBody` and `requestBody` schema reference compliance with json:api
* [ ] Map Psr Requests to Data Transfer Objects
* [ ] Validate Psr Request with application business logic, and build Psr Response with json:api errors
* [ ] Map application models to Psr Response compliance with json:api structure

## Usage

### Install
`composer require free-elephants/json-api-toolkit`

### Build FastRoute\Dispatcher by OAS3 spec

See https://github.com/nikic/FastRoute. 

```php
use FreeElephants\JsonApiToolkit\Routing\FastRoute\DispatcherFactory;

$factory = new DispatcherFactory();
$dispatcher = $factory->buildDispatcher(<<<YAML
paths: 
  /articles:
    get:
      operationId: ArticlesCollectionHandler::handle
YAML
);

$dispatcher->dispatch('GET', '/articles'); // return [Dispatcher::FOUND, 'ArticlesCollectionHandler::handle', []]
```

Extract from operationId  callable string Psr-15 RequestHandler implementation class name (for instantiate with your DI):
```php
use FreeElephants\JsonApiToolkit\Routing\FastRoute\DispatcherFactory;
use FreeElephants\JsonApiToolkit\Routing\FastRoute\PsrHandlerClassNameNormalizer;

$factory = new DispatcherFactory(new PsrHandlerClassNameNormalizer());
$dispatcher = $factory->buildDispatcher(<<<YAML
paths: 
  /articles:
    get:
      operationId: ArticlesCollectionHandler::handle
YAML
);

$dispatcher->dispatch('GET', '/articles'); // return [Dispatcher::FOUND, 'ArticlesCollectionHandler::class', []]
``` 
Or implement your own `OperationHandlerNormalizerInterface` if needed.


Work with different OAS sources:
```php
use FreeElephants\JsonApiToolkit\Routing\FastRoute\DispatcherFactory;
use \FreeElephants\JsonApiToolkit\OasToolsAdapter as Parsers;

(new DispatcherFactory(null, new Parsers\YamlStringParser()))->buildDispatcher(<<<YAML
openapi: 3.0.0
YAML
); // default behavior

(new DispatcherFactory(null, new Parsers\YamlFileParser()))->buildDispatcher('opeanapi.yaml');

(new DispatcherFactory(null, new Parsers\JsonStringParser()))->buildDispatcher(<<<JSON
{
  "openapi": "3.0.0",
  "info": ...
}
JSON
);

(new DispatcherFactory(null, new Parsers\JsonFileParser()))->buildDispatcher('swagger.json');
```

