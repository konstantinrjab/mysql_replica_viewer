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
- set up master and slave
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
- up docker-compose from docker/master and docker/slave respectively
- set up master and slave
run on master
```shell
docker exec rlc_php bash -c "cd scripts && php migrate.php"
docker exec rlc_php bash -c "cd scripts && php load_server.php" &>/dev/null &
docker exec rlc_php bash -c "cd scripts && php websocket_server.php" &>/dev/null &
```