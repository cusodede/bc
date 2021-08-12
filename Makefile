build:
	docker-compose up -d --build

up:
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