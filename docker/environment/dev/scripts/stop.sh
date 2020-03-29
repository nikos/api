#!/usr/bin/env bash

case "$OSTYPE" in
  darwin*) projectRoot=$(stat -f $(dirname $0)/../../../..) ;;
  *) projectRoot=$(readlink -f $(dirname $0)/../../../..) ;;
esac
dockerDevRoot=$projectRoot/docker/environment/dev

cd $dockerDevRoot
docker-compose stop
