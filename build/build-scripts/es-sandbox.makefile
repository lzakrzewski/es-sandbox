ifeq (setup-es-sandbox-test, $(firstword $(MAKECMDGOALS)))
	PHP_VERSION := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(PHP_VERSION):;@:)
endif

ifeq (setup-es-sandbox-dev, $(firstword $(MAKECMDGOALS)))
	PHP_VERSION := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(PHP_VERSION):;@:)
endif

ifeq (setup-es-sandbox, $(firstword $(MAKECMDGOALS)))
	PHP_VERSION := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(PHP_VERSION):;@:)
endif

ifeq ($(PHP_VERSION), 5.6)
    PHP_VERSION = 5.6
else
	PHP_VERSION = 7
endif

setup-es-sandbox: \
    setup-es-sandbox-dev

setup-es-sandbox-test: \
	setup-containers \
    clear-cache-test \
    setup-database-test

setup-es-sandbox-dev: \
    setup-containers \
    clear-cache-dev \
    setup-database-dev

setup-containers: \
	setup-mysql \
	setup-event-store \
	setup-php \

tear-down-es-sandbox: \
	tear-down-event-store \
	tear-down-mysql \
	tear-down-php

test:
	@docker exec -i $(PHP_IMAGE) composer test

test-ci:
	@docker exec -i $(PHP_IMAGE) composer test-ci
