#!/usr/bin/sh
mariadb ${MYSQL_DATABASE} -uroot -p${MYSQL_ROOT_PASSWORD} < /root/${MYSQL_DATABASE}.sql
echo "Restauration terminée"