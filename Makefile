phpunit:
	docker-compose exec -e XDEBUG_MODE=coverage php vendor/bin/phpunit

phpstan:
	docker-compose exec php vendor/bin/phpstan analyse -c phpstan.neon --memory-limit=-1 --xdebug

phpcs:
	docker-compose exec php vendor/bin/phpcs

phpcbf:
	docker-compose exec php vendor/bin/phpcbf

eslint:
	docker-compose run --rm node yarn run check

eslint-fix:
	docker-compose run --rm node yarn run eslint --fix . --ext .js,.svelte

test: phpunit phpstan phpcs eslint

yarn_install:
	docker-compose run --rm node yarn install --no-progress

composer_install:
	docker-compose exec php composer install --no-progress

assets:
	docker-compose run --rm node yarn build

upgrade:
	docker-compose run --rm node yarn upgrade
	docker-compose exec php composer upgrade -W

up:
	docker-compose up -d --build --quiet-pull

down:
	docker-compose down

migrate:
	docker-compose exec php php yii migrate --interactive=0

rollup:
	docker-compose run --rm node yarn rollup

sass:
	docker-compose run --rm node yarn sass

favicons:
	docker-compose run --rm node yarn favicons
