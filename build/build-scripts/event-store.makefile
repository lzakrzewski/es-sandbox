EVENT_STORE_IMAGE          = event-store
EVENT_STORE_CONTAINER_PORT = 2113
EVENT_STORE_EXPOSED_PORT   = 2113

ifeq (event-store, $(firstword $(MAKECMDGOALS)))
	ARGV := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(ARGV):;@:)
endif

setup-event-store: \
	tear-down-event-store \
	run-event-store \
	create-user-event-store \

tear-down-event-store:
	-@docker rm -f $(EVENT_STORE_IMAGE) > /dev/null

run-event-store:
	@docker run --name $(EVENT_STORE_IMAGE) -d -p $(EVENT_STORE_EXPOSED_PORT):$(EVENT_STORE_CONTAINER_PORT) adbrowne/eventstore

create-user-event-store:
	@docker exec -i $(EVENT_STORE_IMAGE) $(BASH_BIN) -c '$(CREATE_USER)'

event-store:
	@docker exec -it -u $(USER_ID) $(EVENT_STORE_IMAGE) $(BASH_BIN)
