###Composer
docker run --rm --interactive --tty --volume ${PWD}:/app composer install --ignore-platform-reqs --no-scripts
docker run --rm --interactive --tty --volume ${PWD}:/app composer dump-autoload -o

###Docker compose
DOCKER_USER=$(id -u):$(id -g) docker-compose -f "./docker/docker-compose.yml" up -d
DOCKER_USER=$(id -u):$(id -g) docker-compose -f "./docker/docker-compose.yml" down

docker run --rm --interactive --tty --volume ${PWD}:/app  composer require react/event-loop react/http