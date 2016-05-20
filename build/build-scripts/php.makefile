PHP_IMAGE              = php7

ifeq (php, $(firstword $(MAKECMDGOALS)))
	ARGV := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(ARGV):;@:)
endif

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
                $(BASH_BIN)

wait-for-mysql:
	docker exec -i $(PHP_IMAGE) ./build/build-scripts/wait-for-mysql.sh

setup-database-dev: \
    wait-for-mysql
	@docker exec -i $(PHP_IMAGE) composer setup-database-dev

setup-database-test: \
    wait-for-mysql
	@docker exec -i $(PHP_IMAGE) composer setup-database-dev

install-composer-deps:
	@docker exec -i $(PHP_IMAGE) composer install -n

clear-cache-test:
	@docker exec -i $(PHP_IMAGE) composer cache-clear-test

clear-cache-dev:
	@docker exec -i $(PHP_IMAGE) composer cache-clear-dev

php:
	@docker exec -it $(PHP_IMAGE) $(BASH_BIN) -c '$(ARGV)'
