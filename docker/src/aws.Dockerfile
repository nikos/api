ROM alpine:edge AS build
MAINTAINER srsh rabenstein@srsh.io
ADD . /app

RUN apk --no-cache add \
		     curl \
		     git \
		     php7 \
		     php7-curl \
		     php7-openssl \
		     php7-iconv \
		     php7-json \
		     php7-mbstring \
		     php7-phar \
             php7-xml \
             php7-xmlwriter \
             php7-tokenizer \
             php7-zip \
             php7-pdo \
             php7-pdo_mysql \
		     php7-dom --repository http://nl.alpinelinux.org/alpine/edge/testing/ 

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 
WORKDIR /app
RUN composer install
RUN composer development-enable