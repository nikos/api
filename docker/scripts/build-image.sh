#!/usr/bin/env bash

if [ -z "$1" ]; then
    echo "Version cannot be empty."
    echo -e "\nUsage:\n$0 version \n"
    exit 1
fi

VERSION=$1
IMAGE_NAME=php-apache
IMAGE_TAG=docker.pkg.github.com/civictechhub/api/$IMAGE_NAME:$VERSION

DOCKER_BUILDKIT=1 docker build \
    --file=$(dirname $0)/../src/api.Dockerfile \
    --target=production \
    --tag=$IMAGE_TAG \
    $(dirname $0)/../../

echo "successfully built image $IMAGE_TAG"