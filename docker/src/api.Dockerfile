##
## install
##

FROM composer:1.10 AS build

COPY ./app /app
# TODO check if dir exists otherwisewarn; this is needed for authentication with github when installing dependencies
COPY ./app/.composer /tmp
#TODO add --no-dev after resolving issue with  Uncaught Error: Undefined constant 'ApiTools\VERSION'
RUN composer install --ignore-platform-reqs --prefer-dist
RUN composer development-disable


##
## base
##
FROM php:7.2-apache as base

RUN apt-get update \
 && apt-get install -y git zlib1g-dev unzip \
 && docker-php-ext-install zip pdo pdo_mysql

# remove the content of /var/lib/apt/lists after installing system dependencies, as these files are not needed anymore
RUN rm -r /var/lib/apt/lists/*

# configure apache2

# enable module rewrite
RUN a2enmod rewrite

RUN echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf

# copy apache2 config
COPY ./docker/src/files/apache2/000-default.conf /etc/apache2/sites-enabled/000-default.conf
COPY ./docker/src/files/apache2/ports.conf /etc/apache2/ports.conf

# would be nice to be able to unexpose port 80
# see https://github.com/moby/moby/issues/3465
EXPOSE 8080

##
## development
##
FROM base AS development

ARG WWW_DATA_USER_ID
ARG WWW_DATA_GROUP_ID

# copy composer binary
COPY --from=composer:1.10 /usr/bin/composer /usr/bin/composer

# install xdebug
RUN apt-get update && \
    pecl install xdebug  && \
    docker-php-ext-enable xdebug
COPY ./docker/src/files/php/xdebug.ini ${PHP_INI_DIR}/conf.d/xdebug.ini

RUN usermod -u ${WWW_DATA_USER_ID} www-data && groupmod -g ${WWW_DATA_GROUP_ID} www-data

RUN chown -R www-data:www-data /var/run/apache2
USER www-data
WORKDIR /var/www

##
## production
##

FROM base as production

# copy build
COPY --chown=www-data:www-data --from=build /app /var/www

RUN chown -R www-data:www-data /var/run/apache2
USER www-data
WORKDIR /var/www