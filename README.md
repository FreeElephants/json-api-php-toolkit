# Json Api Toolkit

## Features: 
* [x] Build FastRoute Dispatcher by defined in OAS3 `operationsIds` values
* [x] Serialize Doctrine entities with Neomerx schemas (resolve issue https://github.com/neomerx/json-api/issues/40)  
* [ ] Generate PHP Data Transfer Objects by OAS3 `reponseBody` and `requestBody` schema reference compliance with json:api
* [x] Map Psr Requests to Data Transfer Objects
* [x] Validate income Psr Request, and build Psr Response with json:api errors
* [x] Map application models to Psr Response compliance with json:api structure

## Usage

### Install
`composer require free-elephants/json-api-toolkit`

### Documentation

Available in [/docs](/docs). 

#### Index:
1. [Routing](/docs/routing.md)
1. [Serialize doctrine entities](/docs/doctrine.md)
1. [DTO from psr server request](/docs/dto-psr7.md)
