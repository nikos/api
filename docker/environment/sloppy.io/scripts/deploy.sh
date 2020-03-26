#!/usr/bin/env bash

cd $(dirname $0)/..

if [ ! -f ".env" ]; then
    echo -e ".env file does not exist. \n"
    exit 1
fi

set -a
. .env
set +a

if [ -z "$MYSQL_PASSWORD" ]; then
    echo -e "MYSQL_PASSWORD cannot be empty. \n"
    exit 1
fi

if [ -z "$MYSQL_ROOT_PASSWORD" ]; then
    echo -e "MYSQL_ROOT_PASSWORD cannot be empty. \n"
    exit 1
fi

sloppy change \
    -var=MYSQL_DATABASE:"$MYSQL_DATABASE" \
    -var=MYSQL_USER:"$MYSQL_USER" \
    -var=MYSQL_PASSWORD:"$MYSQL_PASSWORD" \
    -var=MYSQL_ROOT_PASSWORD:"$MYSQL_ROOT_PASSWORD" \
    -var=API_CORS_ALLOWED_ORIGIN:"$API_CORS_ALLOWED_ORIGIN" \
    sloppy.yml