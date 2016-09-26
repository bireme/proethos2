FROM php:5.6-cli

COPY ./entrypoint.sh /entrypoint.sh
RUN sh /entrypoint.sh

COPY ./php.ini /usr/local/etc/php/
WORKDIR /var/www/symfony/

COPY ./runserver.sh /runserver.sh
