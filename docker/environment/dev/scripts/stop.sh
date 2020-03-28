#!/usr/bin/env bash

projectRoot=$(readlink -f $(dirname $0)/../../../..)
dockerDevRoot=$projectRoot/docker/environment/dev

cd $dockerDevRoot
docker-compose stop
