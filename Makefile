MYSQL_IMAGE            = mysql
PHP_IMAGE              = php7
EVENT_STORE_IMAGE      = event-store

setup-es-sandbox: \
    setup-event-store \
    setup-mysql \
    setup-php \

tear-down-es-sandbox: \
    tear-down-event-store \
    tear-down-mysql \
    tear-down-php \

setup-event-store: \
    tear-down-event-store
	@docker run --name $(EVENT_STORE_IMAGE) -d -p 2113:2113 -p 1113:1113 adbrowne/eventstore

tear-down-event-store:
	-@docker kill $(EVENT_STORE_IMAGE) > /dev/null
	-@docker rm $(EVENT_STORE_IMAGE) > /dev/null

event-store:
	@docker exec -it $(EVENT_STORE_IMAGE) /bin/bash

setup-mysql: \
    tear-down-mysql
	@docker run --name $(MYSQL_IMAGE) -e MYSQL_ROOT_PASSWORD=changeit -d mysql:5.7

tear-down-mysql:
	-@docker kill $(MYSQL_IMAGE) > /dev/null
	-@docker rm $(MYSQL_IMAGE) > /dev/null

mysql:
	@docker exec -it $(MYSQL_IMAGE) /bin/bash

setup-php: \
	tear-down-php
	@docker run --name $(PHP_IMAGE) -d php:7.0.6

tear-down-php:
	-@docker kill $(PHP_IMAGE) > /dev/null
	-@docker rm $(PHP_IMAGE) > /dev/null

php:
	@docker exec -it $(PHP_IMAGE) /bin/bash