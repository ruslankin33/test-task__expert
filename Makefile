init: docker-down-clear docker-pull docker-build docker-up app-init
app-init: app-permissions app-composer-install app-wait-db app-migrations app-fixtures

docker-up:
	docker compose up -d

docker-down:
	docker compose down --remove-orphans

docker-down-clear:
	docker compose down -v --remove-orphans

docker-pull:
	docker compose pull --ignore-pull-failures

docker-build:
	docker compose build --pull

app-permissions:
	docker compose run --rm php chmod -R 777 /var/www/html/var

app-composer-install:
	docker compose exec php composer install

app-wait-db:
	docker compose exec php wait-for-it database:3306 -t 30

app-migrations:
	docker compose exec php bin/console d:m:m --no-interaction

app-fixtures:
	docker compose exec php bin/console doctrine:fixtures:load --no-interaction

app-test:
	docker compose exec php bin/phpunit