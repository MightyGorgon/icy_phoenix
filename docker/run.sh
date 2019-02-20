rm -f config.php
touch config.php && chmod 0777 config.php
docker stop icy_phoenix
docker run --name icy_phoenix -d --rm -e LOG_STDOUT=1 -e LOG_STDERR=1 -e LOG_LEVEL=debug -p 8080:80 -v $PWD/../icy_phoenix_plugins:/var/www/icy_phoenix_plugins -v $PWD:/var/www/html -v $PWD/docker/init.sql:/docker-entrypoint-initdb.d/init.sql:ro fauria/lamp && docker exec icy_phoenix bash -c "until mysqladmin --user=root --password= --host 127.0.0.1 ping --silent &> /dev/null ; do sleep 2; done ; cat /docker-entrypoint-initdb.d/init.sql | mysql -u root"
