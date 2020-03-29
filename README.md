# CivicTechHub Api

Based on https://github.com/laminas-api-tools/api-tools-skeleton.

## Development

Requirement:
1. install [Docker](https://docs.docker.com/install/)
2. install [Docker Compose](https://docs.docker.com/compose/install/)
    * Min version 1.25.1 is required for Buildkit support

Install:
1. clone this repository
2. run `make install` in reporitory root
    * if you dont have `make` run `docker/environment/dev/scripts/install.sh`

Start/Stop:
* `make start` (or `docker/environment/dev/scripts/start.sh`)
* `make stop` (or `docker/environment/dev/scripts/stop.sh`)

Swagger documentation available at http://localhost:8080/api-tools/swagger.
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
