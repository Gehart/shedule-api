exec:
	docker-compose exec php-fpm /bin/bash
down:
	docker-compose down
up:
	docker-compose up --build -d

