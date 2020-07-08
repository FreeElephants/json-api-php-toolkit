# Validation Incoming Requests 

## Validation by Rules

Build with [rakit/validation](https://github.com/rakit/validation/) package.

See `FreeElephants\JsonApiToolkit\Middleware\Validation\Validation` middleware.  

## Validation by Swagger Spec

Build with [league/openapi-psr7-validator](https://github.com/thephpleague/openapi-psr7-validator) package. 

See `FreeElephants\JsonApiToolkit\Middleware\SwaggerSpecificationRequestValidator` middleware. 

_Note: need configured PSR-6 cache for best performance._

