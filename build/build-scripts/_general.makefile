MYSQL_IMAGE            = mysql
PHP_IMAGE              = php7
EVENT_STORE_IMAGE      = event-store

CONTAINER_WORK_DIR     = /var/www/es-sandbox
CONTAINER_HOME         = /root

setup-es-sandbox: \
	setup-event-store \
	setup-mysql \
	setup-php \

tear-down-es-sandbox: \
	tear-down-event-store \
	tear-down-mysql \
	tear-down-php \

clear-cache-test:
	-@docker exec -i $(PHP_IMAGE) composer cache-clear-test

clear-cache-dev:
	-@docker exec -i $(PHP_IMAGE) composer cache-clear-dev

test:
	@docker exec -i $(PHP_IMAGE) composer test

prepare-ci: \
	clear-cache-test
	@docker exec -i $(PHP_IMAGE) composer setup-database-test

test-ci:
	@docker exec -i $(PHP_IMAGE) composer test-ci
