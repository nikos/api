install:
	docker-compose build
	docker-compose run api composer install
	docker-compose run api composer development-enable
	docker-compose run api vendor/bin/phinx migrate

run: install
	docker-compose up -d
