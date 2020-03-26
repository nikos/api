# CivicTechHub Api

Based on https://github.com/laminas-api-tools/api-tools-skeleton.

## Development

Requirement:
1. install [Docker](https://docs.docker.com/install/)
2. install [Docker Compose](https://docs.docker.com/compose/install/)
For Buildkit support (for faster builds) make sure you have at least version 1.25.1 of docker compose.

* clone this repository
* change directory to `docker/environment/dev`
* `cp .env.template .env`
* replace WWW_DATA_USER_ID and WWW_DATA_GROUP_ID with your user id (`id -u`) and group id (`id -g`)
* change XDEBUG_REMOTE_HOST if necessary (this is the host that xdebug connects to as seen from within the docker container)
* change host ports if needed
* `docker-compose build` (for buildkit support which is faster but experimental, run `COMPOSE_DOCKER_CLI_BUILD=1 DOCKER_BUILDKIT=1 docker-compose build`; it requies docker-compose version minimum 1.25.1)
* `docker-compose run api composer install`
* `docker-compose run api composer development-enable`
* `docker-compose up`
* `docker-compose run api vendor/bin/phinx migrate`
* `docker-compose run api vendor/bin/phinx seed:run`
* `docker-compose run api php bin/console.php import-from-csv /var/www/db/seeds/input.csv`

The api can be accessed through http://localhost:8080 (or whatever `HOST_HTTP_PORT` you set in .env).
The mysql database can be accessed through localhost:3306 (or whatever `HOST_MYSQL_PORT` you set in .env)

A postman collection and environment for testing the api is included in `example/postman`.

## Deployment

### Build and push image to github docker registry
First, get a token from github: https://help.github.com/en/packages/using-github-packages-with-your-projects-ecosystem/configuring-docker-for-use-with-github-packages.

Login to registry:
`cat path-to-your-token-file | docker login docker.pkg.github.com -u your-github-username --password-stdin`  (see https://github.com/civictechhub/api/packages?package_type=Docker)

Build images (provide version number like for example 1.0.0):
`docker/scripts/build-images.sh version-number`

Push to registry (image tag is printed at the end of last command):
`docker push image-tag`

### Sloppy.io
For now we host on sloppy.io. WIth free plan, no private registry is allowed. So we user images from geiru/civictechhub-api-php-apache.