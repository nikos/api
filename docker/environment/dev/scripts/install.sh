#!/usr/bin/env bash

case "$OSTYPE" in
  darwin*) projectRoot=$(stat -f $(dirname $0)/../../../..) ;;
  *) projectRoot=$(readlink -f $(dirname $0)/../../../..) ;;
esac
dockerDevRoot=$projectRoot/docker/environment/dev

echo Please provide your airtable api key. It will be written to the file docker/environment/dev/.env.
echo It is needed to import data from Airtable. It will only reside on your computer and not be shared with anyone.
echo See README.md for information on where to get it from.
read airtableApiKey

# copy .env file and replace user and group id
cp $dockerDevRoot/.env.template $dockerDevRoot/.env

userId=$(id -u)
groupId=$(id -g)

if [ $userId -eq 0 ] || [ $groupId -eq 0 ]
then
    echo "It seems you are running the install script as root. This is not recommended."
    while true; do
        read -p "Do you wish to proceed anyways?" yn
        case $yn in
            [Yy]* ) break;;
            [Nn]* ) exit 1;;
            * ) echo "Please answer yes or no.";;
        esac
    done
else
    sed -i "s!WWW_DATA_USER_ID=1000!WWW_DATA_USER_ID=$userId!g" $dockerDevRoot/.env
    sed -i "s!WWW_DATA_GROUP_ID=1000!WWW_DATA_GROUP_ID=$groupId!g" $dockerDevRoot/.env
fi

sed -i "s!AIRTABLE_API_KEY=!AIRTABLE_API_KEY=$airtableApiKey!g" $dockerDevRoot/.env

# create .composer directory
mkdir -p $projectRoot/app/.composer

cd $dockerDevRoot
COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose build
docker-compose run api composer install
docker-compose run api composer development-enable
docker-compose run api /var/scripts/wait-for-it.sh db:3306 -- bin/migrate-database.sh
docker-compose run api /var/scripts/wait-for-it.sh db:3306 -- bin/seed-database.sh
docker-compose run api /var/scripts/wait-for-it.sh db:3306 -- bin/import-data-from-external-sources.sh
