##
## base
##
FROM php:7.2-apache as base

RUN apt-get update \
 && apt-get install -y git zlib1g-dev \
 && docker-php-ext-install zip pdo pdo_mysql \
 && a2enmod rewrite \
 && sed -i 's!/var/www/html!/var/www/public!g' /etc/apache2/sites-available/000-default.conf \
 && mv /var/www/html /var/www/public \
 && curl -sS https://getcomposer.org/installer \
  | php -- --install-dir=/usr/local/bin --filename=composer \
 && echo "AllowEncodedSlashes On" >> /etc/apache2/apache2.conf

ARG WWW_DATA_USER_ID
ARG WWW_DATA_GROUP_ID

RUN usermod -u ${WWW_DATA_USER_ID} www-data && groupmod -g ${WWW_DATA_GROUP_ID} www-data

WORKDIR /var/www

##
## development
##
FROM base AS development

# install xdebug
RUN apt-get update && \
    pecl install xdebug  && \
    docker-php-ext-enable xdebug
COPY ./dev_environment/docker/xdebug.ini ${PHP_INI_DIR}/conf.d/xdebug.ini

ARG WWW_DATA_USER_ID
ARG WWW_DATA_GROUP_ID

RUN usermod -u ${WWW_DATA_USER_ID} www-data && groupmod -g ${WWW_DATA_GROUP_ID} www-data

WORKDIR /var/www