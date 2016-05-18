setup-event-store: \
	tear-down-event-store
	@docker run --name $(EVENT_STORE_IMAGE) -d -p 2113:2113 -p 1113:1113 adbrowne/eventstore

tear-down-event-store:
	-@docker kill $(EVENT_STORE_IMAGE) > /dev/null
	-@docker rm $(EVENT_STORE_IMAGE) > /dev/null

event-store:
	@docker exec -it $(EVENT_STORE_IMAGE) /bin/bash
