MYSQL_IMAGE            = mysql
MYSQL_CONTAINER_PORT   = 3306
MYSQL_EXPOSED_PORT     = 13306

ifeq (mysql, $(firstword $(MAKECMDGOALS)))
	ARGV := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(ARGV):;@:)
endif

setup-mysql: \
	tear-down-mysql \
	run-mysql \
	create-user-mysql

run-mysql:
	@docker run --name $(MYSQL_IMAGE) -e MYSQL_ROOT_PASSWORD=changeit -i -d -p $(MYSQL_EXPOSED_PORT):$(MYSQL_CONTAINER_PORT) mysql:5.7

tear-down-mysql:
	-@docker rm -f $(MYSQL_IMAGE) > /dev/null

create-user-mysql:
	@docker exec -i $(MYSQL_IMAGE) $(BASH_BIN) -c '$(CREATE_USER)'

mysql:
	@docker exec -it -u $(USER_ID) $(MYSQL_IMAGE) $(BASH_BIN)