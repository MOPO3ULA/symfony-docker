OS := $(shell uname)

start:
ifeq ($(OS),Darwin)
	docker volume create --name=app-sync
	docker-compose -f docker-compose-dev.yml up -d --build
	docker-sync clean
	docker-sync start
	docker exec -it symfony-docker_php_1 php doctrine:schema:update --force
else
	docker-compose up -d
	docker exec -it symfony-docker_php_1 php bin/console doctrine:schema:update --force
endif

stop:
ifeq ($(OS),Darwin)
	docker-compose down
	docker-sync stop
else
	docker-compose stop
endif