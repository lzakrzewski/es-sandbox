CONTAINER_WORK_DIR     = /var/www/es-sandbox
CONTAINER_HOME         = /root
BASH_BIN               = /bin/bash
PLATFORM              := $(shell uname)

UID                    = $(shell id -u)
GID                    = $(shell id -g)
UNAME                  = $(shell id -un)

BUILD_ARGS       = --build-arg UID=$(UID) \
                   --build-arg GID=$(GID) \
                   --build-arg UNAME=$(UNAME)