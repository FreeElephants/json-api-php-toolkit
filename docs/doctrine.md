# Serialize Doctrine ORM Entities

Library use [neomerx/json-api](https://github.com/neomerx/json-api/) package for encode data to json:api structures.  

Doctrine wrap your own class to Proxy, this issues explained in https://github.com/neomerx/json-api/issues/40.

Library provide solution for it. Example of dependency injection ([free-elephants/di](https://github.com/FreeElephants/php-di) container configuration):

```php

use Doctrine\ORM\EntityManagerInterface;
use FreeElephants\DI\InjectorBuilder;
use FreeElephants\JsonApiToolkit\Neomerx\Encoder;
use Neomerx\JsonApi\Contracts\Encoder\EncoderInterface;

/** @var EntityManagerInterface $entityManager */
$entityManager = require 'bootstrap.php'; // your configuration file for doctrine
$schemas = require_once 'jsonapi-schemas.php'; // your json:api schemas map for neomerx encoder

Encoder::setEntityManager($entityManager);

return [
    InjectorBuilder::INSTANCES_KEY => [
        EncoderInterface::class => Encoder::instance($schemas),
    ]
];

```
