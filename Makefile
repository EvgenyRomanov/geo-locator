init: down build up

test:
	@docker compose run --rm app-php-cli composer test

build:
	docker compose build --pull

up:
	docker compose up -d

down:
	docker compose down --remove-orphans

app:
	@docker compose run --rm app-php-cli composer app

lint:
	docker compose run --rm app-php-cli composer php-cs-fixer fix -- --dry-run --diff

cs-fix:
	docker compose run --rm app-php-cli composer php-cs-fixer fix

analyze:
	docker compose run --rm app-php-cli composer psalm -- --no-diff


.PHONY: test build up down app init
