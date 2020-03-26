#!/usr/bin/env bash

if [ -z "$1" ]; then
    echo "Version cannot be empty."
    echo -e "\nUsage:\n$0 version \n"
    exit 1
fi

VERSION=$1
PHP_APACHE_IMAGE_TAG=docker.pkg.github.com/civictechhub/api/php-apache:$VERSION
DB_IMAGE_TAG=docker.pkg.github.com/civictechhub/api/db:$VERSION

DOCKER_BUILDKIT=1 docker build \
    --file=$(dirname $0)/../src/api.Dockerfile \
    --target=production \
    --tag=$PHP_APACHE_IMAGE_TAG \
    $(dirname $0)/../../

DOCKER_BUILDKIT=1 docker build \
    --file=$(dirname $0)/../src/db.Dockerfile \
    --target=production \
    --tag=$DB_IMAGE_TAG \
    $(dirname $0)/../../


echo "images: $PHP_APACHE_IMAGE_TAG and $DB_IMAGE_TAG"