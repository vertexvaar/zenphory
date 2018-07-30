
up:
	docker-compose up -d

down:
	docker-compose down

bash:
	docker-compose exec -u application app bash

install:
	docker-compose exec -u application app composer install -d /app

interpolate:
	docker-compose exec -u application app /app/bin/zenphory interpolate

print:
	docker-compose exec -u application app /app/bin/zenphory print
