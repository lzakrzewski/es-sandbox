MYSQL_IMAGE            = mysql
MYSQL_DOCKER_DIR       = docker/mysql
PHP_IMAGE              = php
PHP_DOCKER_DIR         = docker/php
EVENT_STORE_IMAGE      = event-store
EVENT_STORE_DOCKER_DIR = docker/event-store

setup-event-store:
	    -@docker kill $(EVENT_STORE_IMAGE) > /dev/null
	    -@docker rm $(EVENT_STORE_IMAGE) > /dev/null
	    @docker build -t $(EVENT_STORE_IMAGE) $(EVENT_STORE_DOCKER_DIR) > /dev/null
	    @docker run -d --name=$(EVENT_STORE_IMAGE) $(EVENT_STORE_IMAGE) > /dev/null

setup-mysql:
	    -@docker kill $(MYSQL_IMAGE) > /dev/null
	    -@docker rm $(MYSQL_IMAGE) > /dev/null
	    @docker build -t $(MYSQL_IMAGE) $(MYSQL_DOCKER_DIR)
	    @docker run -d --name=$(MYSQL_IMAGE) $(MYSQL_IMAGE)


