# PHP Test

## Setup

- `touch docker/cli/data/.bash_history`

- `docker-compose run --rm cli`

- `cd /srv/api && composer install -n && bin/console doctrine:migrations:migrate -n`

- `cd /srv/client && composer install -n && exit`

## Run

- `docker-compose up -d client`

- http://localhost:8080

## Dev

### PHP CLI

- `docker-compose run --rm cli`

### Adminer

Password: docker

- `docker-compose up adminer`

- http://localhost:8082/?server=db&username=root

### API

- `docker-compose up api`

- http://localhost:8081/docs
