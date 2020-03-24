# Covid19 Civic Tech Api

Based on https://github.com/laminas-api-tools/api-tools-skeleton.

## development

* clone this repository
* change directory to repository root
* `cp .env.template .env`
* replace WWW_DATA_USER_ID and WWW_DATA_GROUP_ID with your user id (`id -u`) and group id (`id -g`)
* change XDEBUG_REMOTE_HOST if necessary (this is the host that xdebug connects to as seen from within the docker container)
* change host ports if needed
* `docker-compose build`
* `docker-compose run api composer install`
* `docker-compose run api composer development-enable`
* `docker-compose up`
* `docker-compose run api vendor/bin/phinx migrate`
* `docker-compose run api vendor/bin/phinx seed:run`
* `docker-compose run api php bin/console.php import-from-csv dev_environment/data/input.csv`

The api can be accessed through http://localhost:8080 (or whatever `HOST_HTTP_PORT` you set in .env).
The mysql database can be accessed through localhost:3306 (or whatever `HOST_MYSQL_PORT` you set in .env)

A postman collection and environment for testing the api is included in `dev_environment/postman`.
  