#!/usr/bin/env bash

projectRoot=$(readlink -f $(dirname $0)/../../../..)
dockerDevRoot=$projectRoot/docker/environment/dev

# copy .env file and replace user and group id
cp $dockerDevRoot/.env.template $dockerDevRoot/.env
sed -i 's!WWW_DATA_USER_ID=1000!WWW_DATA_USER_ID=$(id -u))!g' $dockerDevRoot/.env
sed -i 's!WWW_DATA_GROUP_ID=1000!WWW_DATA_GROUP_ID=$(id -u))!g' $dockerDevRoot/.env

# create .composer directory
mkdir -p $projectRoot/app/.composer

cd $dockerDevRoot
COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose build
docker-compose run api composer install
docker-compose run api composer development-enable
docker-compose run api /var/scripts/wait-for-it.sh db:3306 -- vendor/bin/phinx migrate
docker-compose run api /var/scripts/wait-for-it.sh db:3306 -- vendor/bin/phinx seed:run
docker-compose run api /var/scripts/wait-for-it.sh db:3306 -- php bin/console.php import-from-csv /var/www/db/seeds/input.csv
