ROOT_DIR ?= $(PWD)

include $(ROOT_DIR)/build/build-scripts/_general.makefile
include $(ROOT_DIR)/build/build-scripts/event-store.makefile
include $(ROOT_DIR)/build/build-scripts/mysql.makefile
include $(ROOT_DIR)/build/build-scripts/php.makefile