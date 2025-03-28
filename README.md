# Json Api Toolkit

[![Build Status](https://github.com/FreeElephants/json-api-php-toolkit/workflows/CI/badge.svg)](https://github.com/FreeElephants/json-api-php-toolkit/actions)
[![codecov](https://codecov.io/gh/FreeElephants/json-api-php-toolkit/branch/master/graph/badge.svg)](https://codecov.io/gh/FreeElephants/json-api-php-toolkit)
[![Installs](https://img.shields.io/packagist/dt/free-elephants/json-api-toolkit.svg)](https://packagist.org/packages/free-elephants/json-api-toolkit)
[![Releases](https://img.shields.io/packagist/v/free-elephants/json-api-toolkit.svg)](https://github.com/FreeElephants/json-api-php-toolkit/releases)

## Features: 
* [x] Build FastRoute Dispatcher by defined in OAS3 `operationsIds` values
* [x] Serialize Doctrine entities with Neomerx schemas (resolve issue https://github.com/neomerx/json-api/issues/40)  
* [ ] Generate PHP Data Transfer Objects by OAS3 `reponseBody` and `requestBody` schema reference compliance with json:api
* [x] Validate incoming Psr Requests with swagger specification and user defined rules, and build Psr Response with json:api errors
* [x] Map application models to Psr Response compliance with json:api structure

## Usage

### Install

`composer require free-elephants/json-api-toolkit`

### Documentation

Available in [/docs](/docs). 

#### Index:
1. [Routing](/docs/routing.md)
1. [Serialize doctrine entities](/docs/doctrine.md)
1. [Validation](/docs/validation.md)

## Development

All dev env is dockerized. Your can use make receipts and `bin/` scripts without locally installed php, composer. 

For run tests with different php version change `PHP_VERSION` value in .env and rebuild image with `make build`.  

## See also:

Package for map PSR-7 http messages with Json Api structure to PHP DTO: https://github.com/FreeElephants/json-api-dto
