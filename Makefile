SHELL := /bin/bash

config:
	echo "UID=$(shell id -u)" > ".env"
	cp "src/.env.example" "src/.env"

init:
	docker exec slimchat_php "composer" "install"
#	docker exec slimchat_php "php" "bin/console" "doctrine:database:create"

docker-up:
	docker-compose up -d

docker-up-recreate:
	docker-compose up --build --force-recreate -d

docker-down:
	docker-compose down

docker-down-remove:
	docker-compose down --volumes

php-bash:
	docker exec -it slimchat_php "/bin/bash"

php-bash-root:
	docker exec -it --user=0 slimchat_php "/bin/bash"
