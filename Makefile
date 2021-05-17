PATH := $(shell pwd)/bin:$(PATH)
$(shell cp -n dev.env .env)
include .env

build:
	docker build --build-arg PHP_VERSION=${PHP_VERSION} -t free-elephants/json-api-php-toolkit .

install: build
	composer install

test:
	vendor/bin/phpunit

