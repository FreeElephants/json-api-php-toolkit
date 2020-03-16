PATH := $(shell pwd)/bin:$(PATH)

build:
	docker build -t free-elephants/json-api-php-toolkit .

install: build
	composer install

test:
	vendor/bin/phpunit

