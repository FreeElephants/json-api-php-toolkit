# Serialize Doctrine ORM Entities

Library use [neomerx/json-api](https://github.com/neomerx/json-api/) package for encode data to json:api structures.  

Doctrine wrap your own class to Proxy, this issues explained in https://github.com/neomerx/json-api/issues/40.

Library provide solution for it. Example of dependency injection ([free-elephants/di](https://github.com/FreeElephants/php-di) container configuration):

```php
# index.php 
use FreeElephants\DI\InjectorBuilder;
use FreeElephants\JsonApiToolkit\Neomerx\Encoder;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;

$components = require_once 'components.php'; 
$container = (new InjectorBuilder())->buildFromArray($components);

Encoder::setPsrContainer($container);
$schemas = require_once 'jsonapi-schemas.php'; // your json:api schemas map for neomerx encoder
$jsonApiEncoder = Encoder::instance($schemas);

$container->setService(EncoderInterface::class, $jsonApiEncoder);

// other application code
```
