CID = $(shell docker ps -aqf "name=base_php-8_1")

build:
	docker-compose up -d --build

start:
	docker-compose up -d

force up:
	docker-compose up -d --build --force-recreate

restart:
	docker-compose restart

down:
	docker-compose down

stop:
	docker-compose stop

exec:
	docker exec -ti $(or $(s), $(service)) bash

composer:
	@docker exec -i ${CID} bash -c "composer install"

migrate:
	@docker exec -i ${CID} bash -c "./yii migrate"

setup:
	./docker/scripts/setup