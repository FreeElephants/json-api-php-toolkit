# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- Data Transfer Object classes generation from swagger spec
- AbstractDocument::fromHttpMessage(), possible constructing DTO from Response and Requests both
- PsrContainerAwareSchemaContainer for injection dependencies to neomerx Schemas

### Changed:
- Mark AbstractDocument::__constructor as final
- Extended neomerx Encoder and Factory don't use EntityManager, but use Psr\Container 

### Deprecated
- AbstractDocument::fromRequest, use fromHttpMessage instead

### Removed
- DoctrineProxyAwareSchemaContainer, use PsrContainerAwareSchemaContainer: it is check Doctrine Proxies too. 

## [0.0.9] - 2020-07-08
### Added
- Typed attributes fields support and nested key-value structures in DTO
- SwaggerSpecificationRequestValidator middleware

## [0.0.8] - 2020-06-25
### Added
- fig/http-message-util package dependency (StatusCodeInterface is using)

### Fixed
- Respond with 400 instead 500 on invalid json, handle it with BodyParser middleware
- Set type in DTO\AbstractResourceObject

## [0.0.7] - 2020-06-12
### Added
- Relationships support in DTO classes
- Factory and Middleware Decorator for assign middleware to methods

### Fixed
- DoctrineProxyAwareSchemaContainer handle not entity classes correct
- Allow all headers (CORS) 
- Handle empty operationId with "not implemented" response

## [0.0.6] - 2020-05-08
### Added
- Middleware: Authorization based on Policies, ErrorHandler, BodyParser
- Psr Application

## [0.0.5] - 2020-05-08
### Added
- Validation subpackage based on rakit/validation
- Request Validation Middleware
- JsonApiResponseFactory
- I18n support

### Removed
- Unused free-elephants/php-di dependency (but suggested) 

## [0.0.4] - 2020-04-27
### Fixed 
- Bump version with merged 0.0.2 changes
- Correct license value

### Changed
- Remove unused zircote/swagger-php package

## [0.0.3] - 2020-04-27 [YANKED]
### Added
- Neomerx Encoder implementation aware about Doctrine Proxies
- DTO super classes

## [0.0.2] - 2020-03-24
### Fixed
- OperitionId to Psr normalizer class name

## [0.0.1] - 2020-03-24
### Added
- FastRoute Dispatcher generation from swagger operationIds

[Unreleased]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.9...HEAD
[0.0.8]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.8...0.0.9
[0.0.8]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.7...0.0.8
[0.0.7]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.6...0.0.7
[0.0.6]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.5...0.0.6
[0.0.5]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.4...0.0.5
[0.0.4]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.3...0.0.4
[0.0.3]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.2...0.0.3
[0.0.2]: https://github.com/FreeElephants/json-api-php-toolkit/compare/0.0.1...0.0.2
[0.0.1]: https://github.com/FreeElephants/json-api-php-toolkit/releases/tag/0.0.1
