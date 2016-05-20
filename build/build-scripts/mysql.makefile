MYSQL_IMAGE            = mysql

setup-mysql: \
	tear-down-mysql
	@docker run --name $(MYSQL_IMAGE) -e MYSQL_ROOT_PASSWORD=changeit -i -d -p 13306:3306 mysql:5.7
	@docker exec -i $(MYSQL_IMAGE) service mysql start

tear-down-mysql:
	-@docker kill $(MYSQL_IMAGE) > /dev/null
	-@docker rm $(MYSQL_IMAGE) > /dev/null

mysql:
	@docker exec -it $(MYSQL_IMAGE) /bin/bash