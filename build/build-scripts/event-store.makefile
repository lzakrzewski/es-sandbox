EVENT_STORE_IMAGE          = event-store
EVENT_STORE_CONTAINER_PORT = 2113
EVENT_STORE_EXPOSED_PORT   = 2113

ifeq (event-store, $(firstword $(MAKECMDGOALS)))
	ARGV := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(ARGV):;@:)
endif

setup-event-store: \
	tear-down-event-store
	@docker run --name $(EVENT_STORE_IMAGE) -d -p $(EVENT_STORE_EXPOSED_PORT):$(EVENT_STORE_CONTAINER_PORT) adbrowne/eventstore

tear-down-event-store:
	-@docker kill $(EVENT_STORE_IMAGE) > /dev/null
	-@docker rm $(EVENT_STORE_IMAGE) > /dev/null

event-store:
	@docker exec -it $(EVENT_STORE_IMAGE) $(BASH_BIN) -c '$(ARGV)'
