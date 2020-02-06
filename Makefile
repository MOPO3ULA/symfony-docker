OS := $(shell uname)

start:
ifeq ($(OS),Darwin)
	docker volume create --name=app-sync
	docker-compose -f docker-compose-dev.yml up -d --build
	docker-sync clean
	docker-sync start
else
	docker-compose up -d
endif

stop:
ifeq ($(OS),Darwin)
	docker-compose stop
	docker-sync stop
else
	docker-compose stop
endif