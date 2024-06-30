FROM php:8.1.0-fpm-alpine


RUN apk add --no-cache curl git build-base zlib-dev oniguruma-dev autoconf bash

RUN apk add --no-cache libpq-dev && docker-php-ext-install pdo_pgsql


ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

USER www-data

COPY ./ /var/www
WORKDIR /var/www


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
#RUN composer install --no-interaction

CMD php-fpm

EXPOSE 9000