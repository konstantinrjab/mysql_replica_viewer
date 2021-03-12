###Composer
docker run --rm --interactive --tty --volume ${PWD}:/app composer install --ignore-platform-reqs --no-scripts
docker run --rm --interactive --tty --volume ${PWD}:/app composer dump-autoload -o

###Docker compose
DOCKER_USER=$(id -u):$(id -g) docker-compose -f "./docker/local/docker-compose.yml" up -d
DOCKER_USER=$(id -u):$(id -g) docker-compose -f "./docker/local/docker-compose.yml" down

###Local usage
```shell
docker run --rm --interactive --tty --volume ${PWD}:/app composer install --ignore-platform-reqs --no-scripts
DOCKER_USER=$(id -u):$(id -g) docker-compose -f "./docker/local/docker-compose.yml" up -d
```
- set up mysql master and slave
run
```shell
docker exec rlc_php bash -c "cd scripts && php migrate.php"
docker exec rlc_php bash -c "cd scripts && php load_server.php" &>/dev/null &
docker exec rlc_php bash -c "cd scripts && php websocket_server.php" &>/dev/null &
```


###Real usage
on master
```shell
docker run --rm --interactive --tty --volume ${PWD}:/app composer install --ignore-platform-reqs --no-scripts
docker-compose -f "./docker/master/docker-compose.yml" up -d
```
on slave
```shell
docker-compose -f "./docker/slave/docker-compose.yml" up -d
```
- set up mysql master and slave
run on master
```shell
docker exec rlc_php bash -c "cd scripts && php migrate.php"
docker exec rlc_php bash -c "cd scripts && php load_server.php" &>/dev/null &
docker exec rlc_php bash -c "cd scripts && php websocket_server.php" &>/dev/null &
```

###How to set up mysql master and slave
master queries:
```sql
CREATE USER 'replication'@'%' IDENTIFIED BY 'jCeeYKW6FVcXHW7Y';
GRANT REPLICATION SLAVE ON *.* TO 'replication'@'%';
```

Then run
SHOW MASTER STATUS\G
and save File and Position values and use it command below

slave queries:
```sql
CHANGE MASTER TO MASTER_HOST = "rlc_mysql_master", MASTER_USER = "replication", MASTER_PASSWORD = "jCeeYKW6FVcXHW7Y", MASTER_LOG_FILE = "mysql-bin.000006", MASTER_LOG_POS = 1954;
START SLAVE;
SHOW SLAVE STATUS\G;
```

if something went wrong:
RESET SLAVE;
then reconfigure master and slave