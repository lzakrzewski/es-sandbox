setup-php: \
	tear-down-php
	@docker build -t $(PHP_IMAGE) build/docker/php
	@docker run \
	    	-tid \
	    	-v $(ROOT_DIR):$(CONTAINER_WORK_DIR) \
		    -v $(HOME)/.composer:$(CONTAINER_HOME)/.composer \
	    	--name $(PHP_IMAGE) \
	    	$(PHP_IMAGE) \
	    	/bin/bash

tear-down-php:
	-@docker kill $(PHP_IMAGE) > /dev/null
	-@docker rm $(PHP_IMAGE) > /dev/null

php:
	@docker exec -it $(PHP_IMAGE) /bin/bash
