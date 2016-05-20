MYSQL_IMAGE            = mysql
MYSQL_CONTAINER_PORT   = 3306
MYSQL_EXPOSED_PORT     = 13306

ifeq (mysql, $(firstword $(MAKECMDGOALS)))
	ARGV := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
  $(eval $(ARGV):;@:)
endif

setup-mysql: \
	tear-down-mysql
	@docker run --name $(MYSQL_IMAGE) -e MYSQL_ROOT_PASSWORD=changeit -i -d -p $(MYSQL_EXPOSED_PORT):$(MYSQL_CONTAINER_PORT) mysql:5.7

tear-down-mysql:
	-@docker kill $(MYSQL_IMAGE) > /dev/null
	-@docker rm $(MYSQL_IMAGE) > /dev/null

mysql:
	@docker exec -it $(MYSQL_IMAGE) $(BASH_BIN) -c '$(ARGV)'