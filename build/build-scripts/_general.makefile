CONTAINER_WORK_DIR     = /var/www/es-sandbox
CONTAINER_HOME         = /root
BASH_BIN               = /bin/bash
PLATFORM              := $(shell uname)

ifeq (root, $(shell whoami))
	CONTAINER_HOME      = /root
	CREATE_USER         =
else ifeq (Linux, $(PLATFORM))
	GROUP_ID            = $(shell id -g)
	USER_ID             = $(shell id -u)
	CONTAINER_USERNAME  = $(shell id -un)
	CONTAINER_GROUPNAME = $(shell id -gn)
	CONTAINER_HOME      = /home/$(CONTAINER_USERNAME)

	CREATE_USER = \
	  groupadd -f -g $(GROUP_ID) $(CONTAINER_GROUPNAME) && \
	  useradd -u $(USER_ID) -g $(CONTAINER_GROUPNAME) $(CONTAINER_USERNAME) && \
	  mkdir --parent $(CONTAINER_HOME) && \
	  chown -R $(CONTAINER_USERNAME):$(CONTAINER_GROUPNAME) $(CONTAINER_HOME)
else
	CONTAINER_HOME      = /root
	CREATE_USER         =
endif