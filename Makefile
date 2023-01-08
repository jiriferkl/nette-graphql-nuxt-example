up:
	docker compose up -d

down:
	docker compose down

logs:
	docker compose logs -f

status:
	docker compose ps

exec:
	docker compose exec php1 bash

init:
	docker compose run php1 bin/console migrations:continue && docker compose run php1 bin/console elastica:create-index && docker compose run php1 bin/console elastica:index-products --all
