#!/bin/bash
set -euo pipefail
shopt -s nullglob

APP_ROOT='/var/www'
HOST_UID=`stat -c %u ${APP_ROOT}`
HOST_GID=`stat -c %g ${APP_ROOT}`
DOCKER_USER_UID=`id -u www-data`
DOCKER_GROUP_GID=`sed -nr "s/^www-data:x:([0-9]+):.*/\1/p" /etc/group`

if [[ "$HOST_UID" != "0" ]]
then
    if [[ "$HOST_UID" != "$DOCKER_USER_UID" ]]
    then
        usermod -u ${HOST_UID} www-data
        if [[ "$HOST_GID" != "$DOCKER_GROUP_GID" ]]
        then
            usermod -g users www-data
        fi
    fi
fi

echo -e "`/sbin/ip route|awk '/default/ { print $3 }'`\tdocker.host.internal" | sudo tee -a /etc/hosts > /dev/null

cd ${APP_ROOT}
usermod -s /bin/ash www-data
chown www-data:users -R ${APP_ROOT}
sed -i 's/root.*/root ALL=(ALL) NOPASSWD: ALL/g' /etc/sudoers
sudo -u www-data sh -c '\
    COMPOSER_MEMORY_LIMIT=-1 	composer install --no-interaction --no-scripts
	'

exec "$@"