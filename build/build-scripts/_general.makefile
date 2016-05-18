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

test:
	@docker exec -it $(PHP_IMAGE) composer test

test-ci:
	@docker exec -it $(PHP_IMAGE) composer test-ci