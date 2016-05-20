#!/usr/bin/env bash

echo -n "Waiting for MySQL to start."
while true; do
        mysql -h"$MYSQL_PORT_3306_TCP_ADDR" -P"$MYSQL_PORT_3306_TCP_PORT" -uroot -p"$MYSQL_ENV_MYSQL_ROOT_PASSWORD" -e exit &>/dev/null
        [ $? -eq 0 ] && break
        printf '.'
        sleep 1
done
echo -e "\nMySQL is ready."