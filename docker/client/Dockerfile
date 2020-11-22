FROM php:7.4-apache

ARG DEBUG_IP

ENV TZ Europe/Prague

RUN apt-get -y update && apt-get -y upgrade && apt-get -y install \
    libicu-dev
RUN apt-get -y clean && apt-get -y autoremove

RUN docker-php-ext-install \
    pdo_mysql \
    intl

RUN ln -s ./php.ini-development /usr/local/etc/php/php.ini
RUN echo "date.timezone = Europe/Prague" >> /usr/local/etc/php/php.ini
RUN ln -s ../mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load

COPY default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /usr/local/etc/php/conf.d
RUN pecl install xdebug
RUN echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)" > xdebug.ini
RUN echo "xdebug.remote_enable=on" >> xdebug.ini
RUN echo "xdebug.remote_autostart=on" >> xdebug.ini
RUN echo "xdebug.remote_host=$DEBUG_IP" >> xdebug.ini

WORKDIR /var/www
