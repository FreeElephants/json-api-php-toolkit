# Routing

## Build FastRoute\Dispatcher by OAS3 spec

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
