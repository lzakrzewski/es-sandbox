setup-php: \
	tear-down-php \
	build-php \
	run-php \
	install-composer-deps \

tear-down-php:
	-@docker kill $(PHP_IMAGE) > /dev/null
	-@docker rm $(PHP_IMAGE) > /dev/null

build-php:
	@docker build -t $(PHP_IMAGE) build/docker/php

run-php:
	@docker run \
                -tid \
                --link $(MYSQL_IMAGE):$(MYSQL_IMAGE) \
                --link $(EVENT_STORE_IMAGE):$(EVENT_STORE_IMAGE) \
                -v $(ROOT_DIR):$(CONTAINER_WORK_DIR) \
                -v $(HOME)/.composer:$(CONTAINER_HOME)/.composer \
                --name $(PHP_IMAGE) \
                $(PHP_IMAGE) \
                /bin/bash

setup-database:
	-@docker exec -i $(PHP_IMAGE) composer setup-database

install-composer-deps:
	-@docker exec -i $(PHP_IMAGE) composer install -n

php:
	@docker exec -it $(PHP_IMAGE) /bin/bash
