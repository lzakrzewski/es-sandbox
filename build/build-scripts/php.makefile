PHP_IMAGE = php

ifeq ($(PHP_VERSION), 5.6)
    PHP_DOCKER_DIR = build/docker/php5.6
else
	PHP_DOCKER_DIR = build/docker/php7
endif

ifeq (php, $(firstword $(MAKECMDGOALS)))
	ARGV := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(ARGV):;@:)
endif

PHP_DOCKER_EXEC = @docker exec -u $(USER_ID) -i $(PHP_IMAGE)

setup-php: \
	tear-down-php \
	build-php \
	run-php \
	create-user-php \
	install-composer-deps \
	show-php-img2 \

show-php-img2:
	@echo $(PHP_IMAGE)

tear-down-php:
	-@docker rm -f $(PHP_IMAGE) > /dev/null

build-php:
	@docker build -t $(PHP_IMAGE) $(PHP_DOCKER_DIR)

run-php:
	@docker run \
                -tid \
                --link $(MYSQL_IMAGE):$(MYSQL_IMAGE) \
                --link $(EVENT_STORE_IMAGE):$(EVENT_STORE_IMAGE) \
                -v $(ROOT_DIR):$(CONTAINER_WORK_DIR) \
                -v $(HOME)/.composer:$(CONTAINER_HOME)/.composer \
                --name $(PHP_IMAGE) \
                $(PHP_IMAGE) \
                $(BASH_BIN)

wait-for-mysql:
	$(PHP_DOCKER_EXEC) ./build/build-scripts/wait-for-mysql.sh

setup-database-dev: \
    wait-for-mysql
	$(PHP_DOCKER_EXEC) composer setup-database-dev

setup-database-test: \
    wait-for-mysql
	$(PHP_DOCKER_EXEC) composer setup-database-dev

install-composer-deps:
	$(PHP_DOCKER_EXEC) composer install -n

clear-cache-test:
	$(PHP_DOCKER_EXEC) composer cache-clear-test

clear-cache-dev:
	$(PHP_DOCKER_EXEC) composer cache-clear-dev

create-user-php:
	@docker exec -i $(PHP_IMAGE) $(BASH_BIN) -c '$(CREATE_USER)'

php:
	@docker exec -u $(USER_ID) -it $(PHP_IMAGE) $(BASH_BIN)

test:
	$(PHP_DOCKER_EXEC) composer test

test-ci:
	$(PHP_DOCKER_EXEC) composer test-ci