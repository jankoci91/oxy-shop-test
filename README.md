# PHP Test

## Setup

- V rootu projektu:

    - `touch docker/app/data/.bash_history`

    - `docker-compose up`

- V app kontejneru:

    - `composer install`

    - `bin/console doctrine:migrations:migrate`

## API Doc

admin@local.host:docker

http://localhost:8080/docs

- Použijte http://localhost:8080/docs#operations-Token-postCredentialsItem pro **získání tokenu**.

- Ve tvaru _Bearer TOKEN_ použijte token po kliknutí na **authorize**.

Není to mnou vytvořený HTML formulář s jQuery (tam někde jsem s JS skončil), ale umí JWT a není to hnusný.

## Adminer

root:docker

http://localhost:8081
