PHP_IMAGE              = php7

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
	@docker exec -u $(USER_ID) -it $(PHP_IMAGE) $(BASH_BIN) -c '$(ARGV)'