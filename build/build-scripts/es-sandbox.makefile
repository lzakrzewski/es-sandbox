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