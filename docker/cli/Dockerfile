FROM php:7.4-cli

RUN apt-get -y update && apt-get -y upgrade && apt-get -y install \
    wget \
    git \
    zip
RUN apt-get -y clean && apt-get -y autoremove

RUN docker-php-ext-install \
    pdo_mysql

RUN wget https://getcomposer.org/installer -O - | php -- --install-dir=/usr/local/bin --filename=composer

WORKDIR /srv
