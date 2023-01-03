SHELL := /bin/bash

init:
	docker exec slimchat_php "composer" "install"

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
